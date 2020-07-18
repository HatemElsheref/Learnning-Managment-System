
@extends('dashboard.layouts.app')

       @section('content')

           <div class="content-wrapper">
               <div class="content">
                   <!-- Top Statistics -->
                   <div class="row">
                       <div class="col-md-6 col-lg-6 col-xl-3">
                           <div class="card widget-block p-4 rounded bg-primary border">
                               <div class="card-block">
                                   <h4 class="text-white my-2">
                                   	@php
                                   	$all=$free_courses+$paid_courses;
                                   	if($all==0){
                                   	$all=1;
                                   	}
                                   	@endphp
                                   {{($free_courses/$all)*100}}%</h4>
                                   <p class="pb-3">Free Courses</p>
                                   <div class="progress my-2" style="height: 5px;">
                                       <div class="progress-bar bg-white" role="progressbar" style="width: {{(($free_courses/$all)*100)}}%;" aria-valuenow="{{(($free_courses/$all)*100)}}" aria-valuemin="0" aria-valuemax="100"></div>
                                   </div>
                               </div>
                           </div>
                       </div>
                       <div class="col-md-6 col-lg-6 col-xl-3">
                           <div class="card widget-block p-4 rounded bg-warning border">
                               <div class="card-block">
                               		@php
                                   	$all=$free_lessons+$paid_lessons;
                                   	if($all==0){
                                   	$all=1;
                                   	}
                                   	@endphp
                                   <h4 class="text-white my-2">{{(($free_lessons/($all))*100)}}%</h4>
                                   <p class="pb-3">Free Lessons</p>
                                   <div class="progress my-2" style="height: 5px;">
                                       <div class="progress-bar bg-white" role="progressbar" style="width: {{(($free_lessons/($all))*100)}}%" aria-valuenow="{{(($free_lessons/($all))*100)}}" aria-valuemin="0" aria-valuemax="100"></div>
                                   </div>
                               </div>
                           </div>
                       </div>
                       <div class="col-md-6 col-lg-6 col-xl-3">
                           <div class="card widget-block p-4 rounded bg-danger border">
                               <div class="card-block">
                               	@php
                                   	$all=$free_files+$paid_files;
                                   	if($all==0){
                                   	$all=1;
                                   	}
                                   	@endphp
                                   <h4 class="text-white my-2">{{(($free_files/($all))*100)}}%</h4>
                                   <p class="pb-3">Free Slides</p>
                                   <div class="progress my-2" style="height: 5px;">
                                       <div class="progress-bar bg-white" role="progressbar" style="width: {{(($free_files/($all))*100)}}%;" aria-valuenow="{{(($free_files/($all))*100)}}" aria-valuemin="0" aria-valuemax="100"></div>
                                   </div>
                               </div>
                           </div>
                       </div>
                       <div class="col-md-6 col-lg-6 col-xl-3">
                           <div class="card widget-block p-4 rounded bg-success border">
                               <div class="card-block">
                               		@php
                                   	$all=$global_exams+$paid_exams;
                                   	if($all==0){
                                   	$all=1;
                                   	}
                                   	@endphp
                                   <h4 class="text-white my-2">{{(($global_exams/($all))*100)}}%</h4>
                                   <p class="pb-3">Free (Global) Exams</p>
                                   <div class="progress my-2" style="height: 5px;">
                                       <div class="progress-bar bg-white" role="progressbar" style="width: {{(($global_exams/($all))*100)}}%;" aria-valuenow="{{(($global_exams/($all))*100)}}" aria-valuemin="0" aria-valuemax="100"></div>
                                   </div>
                               </div>
                           </div>
                       </div>
                   </div>
                   <div class="row">
                       <div class="col-md-6 col-lg-6 col-xl-3">
                           <div class="media widget-media p-4 bg-white border">
                               <i class="mdi mdi-account-key-outline text-blue mr-4"></i>
                               <div class="media-body align-self-center">
                                   <h4 class="text-primary mb-2">{{$staff}}</h4>
                                   <p>Staff</p>
                               </div>
                           </div>
                       </div>
                       <div class="col-md-6 col-lg-6 col-xl-3">
                           <div class="media widget-media p-4 bg-white border">
                               <i class="mdi mdi-account-multiple-outline text-danger mr-4"></i>
                               <div class="media-body align-self-center">
                                   <h4 class="text-primary mb-2">{{$students}}</h4>
                                   <p>Users</p>
                               </div>
                           </div>
                       </div>
                       <div class="col-md-6 col-lg-6 col-xl-3">
                           <div class="media widget-media p-4 rounded bg-white border">
                               <i class="mdi mdi-account-network-outline text-success mr-4"></i>
                               <div class="media-body align-self-center">
                                   <h4 class="text-primary mb-2">{{$instructors}}</h4>
                                   <p>Instructors</p>
                               </div>
                           </div>
                       </div>
                       <div class="col-md-6 col-lg-6 col-xl-3">
                           <div class="media widget-media p-4 rounded bg-white border">
                               <i class="mdi mdi-cart-outline text-warning mr-4"></i>
                               <div class="media-body align-self-center">
                                   <h4 class="text-primary mb-2">{{$paid_orders+$pending_orders}}</h4>
                                   <p>Orders</p>
                               </div>
                           </div>
                       </div>

                       <div class="col-md-6 col-lg-6 col-xl-3">
                           <div class="media widget-media p-4 rounded bg-white border">
                               <i class="mdi mdi-video-outline text-blue mr-4"></i>
                               <div class="media-body align-self-center">
                                   <h4 class="text-primary mb-2">{{$free_courses+$paid_courses}}</h4>
                                   <p>Courses</p>
                               </div>
                           </div>
                       </div>
                       <div class="col-md-6 col-lg-6 col-xl-3">
                           <div class="media widget-media p-4 rounded bg-white border">
                               <i class="mdi mdi-play-circle-outline text-danger mr-4"></i>
                               <div class="media-body align-self-center">
                                   <h4 class="text-primary mb-2">{{$free_lessons+$paid_lessons}}</h4>
                                   <p>Lessons</p>
                               </div>
                           </div>
                       </div>
                       <div class="col-md-6 col-lg-6 col-xl-3">
                           <div class="media widget-media p-4 rounded bg-white border">
                               <i class="mdi mdi-file-outline text-success mr-4"></i>
                               <div class="media-body align-self-center">
                                   <h4 class="text-primary mb-2">{{$free_files+$paid_files}}</h4>
                                   <p>Attached Files</p>
                               </div>
                           </div>
                       </div>
                       <div class="col-md-6 col-lg-6 col-xl-3">
                           <div class="media widget-media p-4 rounded bg-white border">
                               <i class="mdi mdi-file-document-outline text-warning mr-4"></i>
                               <div class="media-body align-self-center">
                                   <h4 class="text-primary mb-2">{{$global_exams+$paid_exams}}</h4>
                                   <p>Exams</p>
                               </div>
                           </div>
                       </div>

                       <div class="col-md-6 col-lg-6 col-xl-3">
                           <div class="media widget-media p-4 rounded bg-white border">
                               <i class="mdi mdi-bank-outline text-blue mr-4"></i>
                               <div class="media-body align-self-center">
                                   <h4 class="text-primary mb-2">{{$universities}}</h4>
                                   <p>Universities</p>
                               </div>
                           </div>
                       </div>
                       <div class="col-md-6 col-lg-6 col-xl-3">
                           <div class="media widget-media p-4 rounded bg-white border">
                               <i class="mdi mdi-view-grid text-danger mr-4"></i>
                               <div class="media-body align-self-center">
                                   <h4 class="text-primary mb-2">{{$departments}}</h4>
                                   <p>Departments</p>
                               </div>
                           </div>
                       </div>
                       <div class="col-md-6 col-lg-6 col-xl-3">
                           <div class="media widget-media p-4 rounded bg-white border">
                               <i class="mdi mdi-star-circle-outline text-success mr-4"></i>
                               <div class="media-body align-self-center">
                                   <h4 class="text-primary mb-2">{{$rate_courses}}</h4>
                                   <p>Courses Reviews</p>
                               </div>
                           </div>
                       </div>
                       <div class="col-md-6 col-lg-6 col-xl-3">
                           <div class="media widget-media p-4 rounded bg-white border">
                               <i class="mdi mdi-star-outline text-warning mr-4"></i>
                               <div class="media-body align-self-center">
                                   <h4 class="text-primary mb-2">{{$rate_lessons}}</h4>
                                   <p>Lessons Reviews</p>
                               </div>
                           </div>
                       </div>

                       <div class="col-md-6 col-lg-6 col-xl-3">
                           <div class="media widget-media p-4 rounded bg-white border">
                               <i class="mdi mdi-trophy-outline text-blue mr-4"></i>
                               <div class="media-body align-self-center">
                                   <h4 class="text-primary mb-2">{{$projects}}</h4>
                                   <p>Projects</p>
                               </div>
                           </div>
                       </div>
                       <div class="col-md-6 col-lg-6 col-xl-3">
                           <div class="media widget-media p-4 rounded bg-white border">
                               <i class="mdi mdi-heart-multiple-outline text-danger mr-4"></i>
                               <div class="media-body align-self-center">
                                   <h4 class="text-primary mb-2">{{$feedback}}</h4>
                                   <p>Feedback</p>
                               </div>
                           </div>
                       </div>
                       <div class="col-md-6 col-lg-6 col-xl-3">
                           <div class="media widget-media p-4 rounded bg-white border">
                               <i class="mdi mdi-square-edit-outline text-success mr-4"></i>
                               <div class="media-body align-self-center">
                                   <h4 class="text-primary mb-2">{{$articles}}</h4>
                                   <p>Courses Articles</p>
                               </div>
                           </div>
                       </div>
                       <div class="col-md-6 col-lg-6 col-xl-3">
                           <div class="media widget-media p-4 rounded bg-white border">
                               <i class="mdi mdi-bookmark text-warning mr-4"></i>
                               <div class="media-body align-self-center">
                                   <h4 class="text-primary mb-2">{{$categories}}</h4>
                                   <p>Categories</p>
                               </div>
                           </div>
                       </div>

                       <div class="col-md-6 col-lg-6 col-xl-3">
                           <div class="media widget-media p-4 rounded bg-white border">
                               <i class="mdi mdi-playlist-edit text-blue mr-4"></i>
                               <div class="media-body align-self-center">
                                   <h4 class="text-primary mb-2">{{$posts}}</h4>
                                   <p>Posts</p>
                               </div>
                           </div>
                       </div>
                       <div class="col-md-6 col-lg-6 col-xl-3">
                           <div class="media widget-media p-4 rounded bg-white border">
                               <i class="mdi mdi-tag-multiple text-danger mr-4"></i>
                               <div class="media-body align-self-center">
                                   <h4 class="text-primary mb-2">{{$tags}}</h4>
                                   <p>Tags</p>
                               </div>
                           </div>
                       </div>

                   </div>

                   <div class="row">
                       <div class="col-xl-8 col-md-12">
                           <!-- Sales Graph -->
                           <div class="card card-default" data-scroll-height="675">
                               <div class="card-header">
                                   <h2>Sales Of The Year</h2>
                               </div>
                               <div class="card-body">
                                   <canvas id="orders2" class="chartjs"></canvas>
                               </div>
                               <div class="card-footer d-flex flex-wrap bg-white p-0">
                                   <div class="col-6 px-0">
                                       <div class="text-center p-4">
                                           <h4>{{$total_orders}}</h4>
                                           <p class="mt-2">Total orders of this year</p>
                                       </div>
                                   </div>
                                   <div class="col-6 px-0">
                                       <div class="text-center p-4 border-left">
                                           <h4>{{DefaultCurrency()}} {{$total_price}}</h4>
                                           <p class="mt-2">Total revenue of this year</p>
                                       </div>
                                   </div>
                               </div>
                           </div>
                       </div>
                       <div class="col-xl-4 col-md-12">
                           <!-- Doughnut Chart -->
                           <div class="card card-default" data-scroll-height="675">
                               <div class="card-header justify-content-center">
                                   <h2>Orders Overview</h2>
                               </div>
                               <div class="card-body" >
                                   <canvas id="orders1" ></canvas>
                               </div>
{{--                               <a href="#" class="pb-5 d-block text-center text-muted"><i class="mdi mdi-download mr-2"></i> Download overall report</a>--}}
                               <div class="card-footer d-flex flex-wrap bg-white p-0">
                                   <div class="col-6">
                                       <div class="py-4 px-4">
                                           <ul class="d-flex flex-column justify-content-between">
                                               <li><i class="mdi mdi-checkbox-blank-circle-outline mr-2" style="color: #ffa128"></i>Order Paid</li>
                                           </ul>
                                       </div>
                                   </div>
                                   <div class="col-6 border-left">
                                       <div class="py-4 px-4 ">
                                           <ul class="d-flex flex-column justify-content-between">
                                               <li class="mb-2"><i class="mdi mdi-checkbox-blank-circle-outline mr-2" style="color: #8061ef"></i>Order Pending</li>
                                           </ul>
                                       </div>
                                   </div>
                               </div>
                           </div>
                       </div>
                   </div>


               </div>





       @endsection
               @push('scripts')
                   <script>
                       /*======== 11. DOUGHNUT CHART ========*/
                       var doughnut = document.getElementById("orders1");
                       if (doughnut !== null) {
                           var myDoughnutChart = new Chart(doughnut, {
                               type: "doughnut",
                               data: {
                                   labels: ["paid", "pending"],
                                   datasets: [
                                       {
                                           label: ["paid", "pending"],
                                           data: ['{{$paid_orders}}', '{{$pending_orders}}'],
                                           backgroundColor: ["#ffa128", "#8061ef"],
                                           borderWidth: 1
                                           // borderColor: ['#4c84ff','#29cc97','#8061ef','#fec402']
                                           // hoverBorderColor: ['#4c84ff', '#29cc97', '#8061ef', '#fec402']
                                       }
                                   ]
                               },
                               options: {
                                   responsive: true,
                                   maintainAspectRatio: false,
                                   legend: {
                                       display: false
                                   },
                                   cutoutPercentage: 75,
                                   tooltips: {
                                       callbacks: {
                                           title: function(tooltipItem, data) {
                                               return "Order : " + data["labels"][tooltipItem[0]["index"]];
                                           },
                                           label: function(tooltipItem, data) {
                                               return data["datasets"][0]["data"][tooltipItem["index"]];
                                           }
                                       },
                                       titleFontColor: "#888",
                                       bodyFontColor: "#555",
                                       titleFontSize: 12,
                                       bodyFontSize: 14,
                                       backgroundColor: "rgba(256,256,256,0.95)",
                                       displayColors: true,
                                       borderColor: "rgba(220, 220, 220, 0.9)",
                                       borderWidth: 2
                                   }
                               }
                           });
                       }
                       /*======== 3. LINE CHART ========*/
                       var ctx = document.getElementById("orders2");
                       if (ctx !== null) {
                           var chart = new Chart(ctx, {
                               // The type of chart we want to create
                               type: "line",

                               // The data for our dataset
                               data: {
                                   labels: [
                                       "Jan",
                                       "Feb",
                                       "Mar",
                                       "Apr",
                                       "May",
                                       "Jun",
                                       "Jul",
                                       "Aug",
                                       "Sep",
                                       "Oct",
                                       "Nov",
                                       "Dec"
                                   ],
                                   datasets: [
                                       {
                                           label: "",
                                           backgroundColor: "transparent",
                                           borderColor: "rgb(82, 136, 255)",
                                           data: [
                                           <?php
                                               foreach ($chart as $k=>$v){
                                                   echo $v.',';
                                               }
                                               ?>
                                           ],
                                           lineTension: 0.3,
                                           pointRadius: 5,
                                           pointBackgroundColor: "rgba(255,255,255,1)",
                                           pointHoverBackgroundColor: "rgba(255,255,255,1)",
                                           pointBorderWidth: 2,
                                           pointHoverRadius: 8,
                                           pointHoverBorderWidth: 1
                                       }
                                   ]
                               },

                               // Configuration options go here
                               options: {
                                   responsive: true,
                                   maintainAspectRatio: false,
                                   legend: {
                                       display: false
                                   },
                                   layout: {
                                       padding: {
                                           right: 10
                                       }
                                   },
                                   scales: {
                                       xAxes: [
                                           {
                                               gridLines: {
                                                   display: false
                                               }
                                           }
                                       ],
                                       yAxes: [
                                           {
                                               gridLines: {
                                                   display: true,
                                                   color: "#eee",
                                                   zeroLineColor: "#eee",
                                               },
                                               ticks: {
                                                   callback: function(value) {
                                                       var ranges = [
                                                           { divider: 1e6, suffix: "M" },
                                                           { divider: 1e4, suffix: "k" }
                                                       ];
                                                       function formatNumber(n) {
                                                           for (var i = 0; i < ranges.length; i++) {
                                                               if (n >= ranges[i].divider) {
                                                                   return (
                                                                       (n / ranges[i].divider).toString() + ranges[i].suffix
                                                                   );
                                                               }
                                                           }
                                                           return n;
                                                       }
                                                       return formatNumber(value);
                                                   }
                                               }
                                           }
                                       ]
                                   },
                                   tooltips: {
                                       callbacks: {
                                           title: function(tooltipItem, data) {
                                               return data["labels"][tooltipItem[0]["index"]];
                                           },
                                           label: function(tooltipItem, data) {
                                               return "$" + data["datasets"][0]["data"][tooltipItem["index"]];
                                           }
                                       },
                                       responsive: true,
                                       intersect: false,
                                       enabled: true,
                                       titleFontColor: "#888",
                                       bodyFontColor: "#555",
                                       titleFontSize: 12,
                                       bodyFontSize: 18,
                                       backgroundColor: "rgba(256,256,256,0.95)",
                                       xPadding: 20,
                                       yPadding: 10,
                                       displayColors: false,
                                       borderColor: "rgba(220, 220, 220, 0.9)",
                                       borderWidth: 2,
                                       caretSize: 10,
                                       caretPadding: 15
                                   }
                               }
                           });
                       }
                   </script>
           @endpush
