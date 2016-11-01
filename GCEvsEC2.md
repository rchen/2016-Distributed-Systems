#Amazon EC2 vs Google Compute Engine
ref <https://cloud.google.com/docs/compare/aws/networking>

##Virtual Machine
###Machine access
*	Amazon EC2: You must include your own SSH key if you want terminal access to the instance.
* Google Compute Engine:  You can create the key when you need it, even if your instance is already running. If you choose to use Compute Engine's browser-based SSH terminal.

###Instance types
*	GCE can  create a machine type customized to your needs.
![Alt text](https://cloud.google.com/images/products/artwork/tailor.png)

###Automatic instance scaling
*	Amazon's Auto Scaling scales instances within a group. The Auto Scaler creates and removes instances according to your chosen scaling plan. Each new instance within the group is created from a launch configuration.
* 	Compute Engine's autoscaler scales instances within a managed instance group. The autoscaler creates and removes instances according to an autoscaling policy. Each new instance within the instance group is created from an instance template.

Compute Engine's autoscaler supports only dynamic scaling. You can create policies based on average CPU utilization, HTTP load balancing serving capacity, or Stackdriver Monitoring metrics.

Amazon's Auto Scaling allows for three scaling plans:

*	**Manual**, in which you manually instruct Auto Scaling to scale up or down.
*	**Scheduled**, in which you configure Auto Scaling to scale up or down at scheduled times.
*	**Dynamic**, in which Auto Scaling scales based on a policy. You can create policies based on either Amazon CloudWatch metrics or Amazon Simple Queue Service (SQS) queues.


##Networking
*	AWS uses POPs to provide a content delivery network (CDN) service, Amazon CloudFront.
* 	Cloud Platform uses POPs to provide Google Cloud CDN and to deliver built-in edge caching for services such as App Engine and Cloud Storage.

Cloud Platform's POPs connect to data centers through Google-owned fiber. This unimpeded connection means that Cloud-Platform-based applications have fast, reliable access to all of the services on Cloud Platform.

##Load Balancing
Amazon ELB service can be integrated with AWS’s Auto Scaling service, adding and removing instances automatically when Auto Scaling scales them up or down.
When you create an Elastic Load Balancer, AWS provides a CNAME to which you can direct traffic.

Like ELB, Compute Engine's load balancer directs traffic to backend instances in one or many zones. Compute Engine's load balancer also has some additional unique features:

*	Compute Engine lets you choose between a network (Layer 4) load balancer, which balances both UDP and TCP traffic regionally, and an HTTP(S) (Layer 7) load balancer, which can balance traffic globally as well as regionally.
*	When you provision a Compute Engine load balancer, you're given a single, globally accessible IP address. This IP address can be used for the lifetime of the load balancer, so it can be used for DNS A Records, whitelists, or configurations in applications.

|  Feature | Amazon ELB  | Compute Engine load balancer|
|---|---|---|
| Network load balancing	  | Yes   | Yes  |
| Support for static IP address	  | No  | Yes   |
| Content-based load balancing	  | No  | Yes  |
| Cross-region load balancing	  | No  | Yes   |
| Scaling pattern	  | Linear  | Real-time  |
| Deployment locality	  | Regional   | Global  |

##Network Pricing
###Google Compute Engine
*	Egress to Google products (such as YouTube, Maps, Drive) : No charge
*	Network (Ingress) : Free

###Amazon EC2
*	使用公有或彈性 IP 地址傳出 Amazon EC2 的資料目的地 : $0.01 每 GB
*	使用公有或彈性 IP 地址傳入 Amazon EC2 的資料來源 : $0.01 每 GB





##BigQuery Command
###Example
Use command to export csv file from GDELT

	bq --format=csv query "SELECT GLOBALEVENTID , Actor1Code, Actor2Code \
	FROM [gdelt-bq:full.events] LIMIT 10"

##Dataproc Job Submit
###Example
	gcloud dataproc jobs submit pyspark --cluster <my-dataproc-cluster> \
	hello-world.py
