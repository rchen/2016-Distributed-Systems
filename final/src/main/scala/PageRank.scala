import org.apache.spark.SparkContext
import org.apache.spark.SparkContext._
import org.apache.spark.SparkConf
import org.apache.spark.graphx._

import com.google.cloud.hadoop.io.bigquery.BigQueryConfiguration
import com.google.cloud.hadoop.io.bigquery.BigQueryOutputFormat
import com.google.cloud.hadoop.io.bigquery.GsonBigQueryInputFormat
import com.google.gson.JsonObject

import org.apache.hadoop.fs.Path
import org.apache.hadoop.io.LongWritable

object PageRank {
  def convertToTuple(record: JsonObject) : (String, String) = {
    //val sqldate = record.get("SQLDATE").getAsString
    val country1 = record.get("Actor1CountryCode").getAsString
    val country2 = record.get("Actor2CountryCode").getAsString
    //val event = record.get("EventRootCode").getAsString
    return (country1, country2)
  }
  
  def convertToJson(pair: (String, Double)) : JsonObject = {
    val country = pair._1
    val rank = pair._2
    val jsonObject = new JsonObject()
    jsonObject.addProperty("country", country)
    jsonObject.addProperty("rank", rank)
    return jsonObject
  }
  
  def pageRank(edge: org.apache.spark.rdd.RDD[(String, String)] ) : org.apache.spark.rdd.RDD[(String, Double)] = {
    val c = edge.flatMap( x => List(x._1 , x._2) ).distinct
    def str2Long(s: String) = s.##.toLong
    val d = c.map(x => (str2Long(x),x))
    val e = edge.map(x => Edge(str2Long(x._1),str2Long(x._2),x._1+":"+x._2))
    val g = Graph(d,e)
    val ranks = g.pageRank(0.0001).vertices
    val ranksByUsername = d.join(ranks).map {case (id, (username, rank)) => (username, rank)}
    ranksByUsername.sortBy( x => x._2).collect().foreach(println)
    return ranksByUsername;
  }
  
  def main(args: Array[String]) {
    val timestamp: Long = System.currentTimeMillis
    val datasetarg = args(0)
    val inputtable = args(1)
    val oututtable = args(2)
    val sc = new SparkContext()
    val conf = sc.hadoopConfiguration
    // Input parameters

    val fullyQualifiedInputTableId = datasetarg + "." + inputtable

    // Output parameters
    val projectId = conf.get("fs.gs.project.id")
    val outputDatasetId = datasetarg
    val outputTableId = oututtable
    val outputTableSchema =
       "[{'name': 'country', 'type': 'STRING'}, {'name': 'rank', 'type': 'FLOAT'}]"

    // Use the Google Cloud Storage bucket for temporary BigQuery export data used
    // by the InputFormat. This assumes the Google Cloud Storage connector for
    // Hadoop is configured.
    val bucket = conf.get("fs.gs.system.bucket")
    // Input configuration
    conf.set(BigQueryConfiguration.PROJECT_ID_KEY, projectId)
    conf.set(BigQueryConfiguration.GCS_BUCKET_KEY, bucket)
    BigQueryConfiguration.configureBigQueryInput(conf, fullyQualifiedInputTableId)
    // This temporary path is used while the InputFormat is live, and must
    // be cleaned up when the job is done.
    val inputTmpDir = s"gs://${bucket}/hadoop/tmp/bigquery/${timestamp}"
    conf.set(BigQueryConfiguration.TEMP_GCS_PATH_KEY, inputTmpDir)

    // Output configuration
    BigQueryConfiguration.configureBigQueryOutput(
       conf, projectId, outputDatasetId, outputTableId, outputTableSchema)
    //conf.set(BigQueryConfiguration.INPUT_QUERY_KEY,"SELECT word , word_count FROM publicdata:samples.shakespeare where word_count > 10")
    conf.set(
        "mapreduce.job.outputformat.class",
        classOf[BigQueryOutputFormat[_,_]].getName)


    // Load data from BigQuery.
    val tableData = sc.newAPIHadoopRDD(
        conf,
        classOf[GsonBigQueryInputFormat],
        classOf[LongWritable],
        classOf[JsonObject]).cache

    // Display 10 results.
    //wordCounts.take(10).foreach(l => println(l))
    val b = tableData.map(entry => convertToTuple(entry._2))
    
    val ranksByUsername = pageRank(b)
    ranksByUsername.sortBy( x => x._2).collect().foreach(println)
    
    // BigQueryOutputFormat discards keys, so set key to null.
    (ranksByUsername
    .map(pair => (null, convertToJson(pair)))
    .saveAsNewAPIHadoopDataset(conf))
    
    val inputTmpDirPath = new Path(inputTmpDir)
    inputTmpDirPath.getFileSystem(conf).delete(inputTmpDirPath, true)
  }
}
