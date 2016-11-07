import org.apache.spark.graphx._
val a = sc.textFile("/Users/jimmy/csv/export.csv").map(line => line.split("\t"))
val b = a.filter(line => line(7)!="").filter(line => line(17)!="").filter(line => line(28)=="19").map(line => (line(7),line(17)))
val c = b.flatMap( x => List(x._1 , x._2) ).distinct
def str2Long(s: String) = s.##.toLong
val d = c.map(x => (str2Long(x),x))
val e = b.map(x => Edge(str2Long(x._1),str2Long(x._2),x._1+":"+x._2))
val g = Graph(d,e)
val ranks = g.pageRank(0.0001).vertices
val ranksByUsername = d.join(ranks).map {case (id, (username, rank)) => (username, rank)}
ranksByUsername.sortBy( x => x._2).foreach(println)
System.exit(0)
