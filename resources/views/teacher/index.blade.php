<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="csrf-token" content="{{csrf_token()}}">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Laravel 8 Ajax CRUD APPLICATION</title>
	<!-- <link rel="stylesheet" type="text/css" href="{{asset('css')}}/app.css"> -->
	<link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap.css')}}">
	<!-- <link rel="stylesheet" type="text/css" href="{{asset('css/sweetalert.min.css')}}"> -->
	<script type="text/javascript" src="{{asset('js/jquery.min.js')}}"></script>
	<!-- <script type="text/javascript" src="{{asset('js/sweetalert.min.js')}}"></script> -->
	<link rel="stylesheet" type="text/css" href="{{asset('css/sweetalert2.min.css')}}">
	<script type="text/javascript" src="{{asset('js/sweetalert2.all.min.js')}}"></script>
	<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>

<div style="padding:30px;"></div>
<div class="container">
	<h2 style="color:red;"><marquee>Laravel 8 Ajax Crud Application</marquee></h2>
	<div class="row">
		<div class="col-sm-8">
			<div class="card">
				<div class="card-header">All Teacher</div>
				<div class="card-body">
					<table class="table table-bodered">
						<thead>
							<tr>
								<th scope="col">#</th>
								<th scope="col">Name</th>
								<th scope="col">Title</th>
								<th scope="col">Institute</th>
								<th scope="col">Action</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="card">
				<div class="card-header">
				<span id="addT">Add New Teacher</span>
				<span id="updateT">Update Teacher</span>
				</div>
				<div class="card-body">
					<div class="form-group">
						<label>Name</label>
						<input type="text" class="form-control" id="name" placeholder="Enter Name">
						<span class="text-danger" id="nameError"></span>
					</div>
					<div class="form-group">
						<label>Title</label>
						<input type="text" class="form-control" id="title" placeholder="Enter Title">
						<span class="text-danger" id="titleError"></span>
					</div>
					<div class="form-group">
						<label>Institute</label>
						<input type="text" class="form-control" id="institute" placeholder="Enter Institute">
						<span class="text-danger" id="instituteError"></span>
					</div>
					<input type="hidden" id="id">
					<button type="submit" id="addButton" onclick="addData()" class="btn btn-sm btn-primary">Add</button>
					<button type="submit" id="updateButton" onclick="updateData()" class="btn btn-sm btn-primary">Update</button>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$("#addT").show();
	$("#addButton").show();
	$("#updateT").hide();
	$("#updateButton").hide();

	$.ajaxSetup({
		headers:{
			'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
		}
	});

	//Get All Data
	function allData(){
		$.ajax({
			type:'GET',
			url:"/teacher/all",
			dataType:"JSON",
			success:function(response){
				// console.log(data);
				var data = "";
				$.each(response, function(key,value){
					// console.log(value.id);
					data = data + "<tr>"
					data = data + "<td>"+value.id+"</td>"
					data = data + "<td>"+value.name+"</td>"
					data = data + "<td>"+value.title+"</td>"
					data = data + "<td>"+value.institute+"</td>"
					data = data + "<td>"
					data = data + "<button class='btn btn-sm btn-primary mr-2' onclick='editData("+value.id+")'>Edit</button>"
					data = data + "<button class='btn btn-sm btn-danger' onclick='deleteData("+value.id+")'>Delete</button>"
					data = data + "</td>"
					data = data + "</tr>";
				});
				$('tbody').html(data);	
			}
		});
	}
	allData();

	// Clear Data
	function clearData(){
		$("#name").val('');
		$("#title").val('');
		$("#institute").val('');
		$("#nameError").text('');
		$('#titleError').text('');
		$('#instituteError').text('');
	}

	//Insert data
	function addData(){
		var name = $("#name").val();
		var title = $("#title").val();
		var institute = $("#institute").val();

		$.ajax({
			type:"POST",
			dataType:"JSON",
			data: {name:name, title:title, institute:institute },
			url:"/teacher/store/",
			success:function(data){
				allData();
				clearData();

				//sweet alert
					Swal.fire(
	 					'Add New Teacher Save Successfully!',
	  					'You clicked the Ok button!',
	 				    'success'
					)
				//end sweet alert
			},
			error:function(error){
				$("#nameError").text(error.responseJSON.errors.name);
				$("#titleError").text(error.responseJSON.errors.title);
				$("#instituteError").text(error.responseJSON.errors.institute);
			}
		});
	}
	//End Insert Data
	
	// Edit Data
	function editData(id){
		$.ajax({
			type:"GET",
			dataType:"JSON",
			url:"/teacher/edit/"+id,
			success:function(data){
				// console.log(data);
			
				$("#addT").hide();
				$("#addButton").hide();
				$("#updateT").show();
				$("#updateButton").show();

				$("#id").val(data.id);
				$("#name").val(data.name);
				$("#title").val(data.title);
				$("#institute").val(data.institute);
			}
		});
	}
	//End Edit Data

	//Start Update Data
	function updateData(){
		var id = $("#id").val();
		var name = $("#name").val();
		var title = $("#title").val();
		var institute = $("#institute").val();
		
		$.ajax({
			type:"POST",
			dataType:"JSON",
			data:{name:name, title:title, institute:institute },
			url:"/teacher/update/"+id,
			success:function(data){
				// console.log(data);
				$("#addT").show();
				$("#addButton").show();
				$("#updateT").hide();
				$("#updateButton").hide();
				allData();
				clearData();

				//sweet alert
					Swal.fire(
	 					'Update Teacher Data Successfully!',
	  					'You clicked the Ok button!',
	 				    'success'
					)
				//end sweet alert
			},
			error:function(error){
				$("#nameError").text(error.responseJSON.errors.name);
				$("#titleError").text(error.responseJSON.errors.title);
				$("#instituteError").text(error.responseJSON.errors.institute);
			}

		});
	}
	//End Update Data

	//Start Delete Ddata
		function deleteData(id){
			Swal.fire({
			  title: 'Are you sure?',
			  text: "You won't be able to delete this!",
			  icon: 'warning',
			  showCancelButton: true,
			  confirmButtonColor: '#3085d6',
			  cancelButtonColor: '#d33',
			  confirmButtonText: 'Yes, delete it!'
			}).then((result) => {
			  if (result.isConfirmed) {
			    Swal.fire(
			      'Deleted!',
			      'Your data has been deleted.',
			      'success'
			    )
			    $.ajax({
						type:"GET",
						dataType:"JSON",
						url:"/teacher/destroy/"+id,
						success:function(data)
						{
							$("#addT").show();
							$("#addButton").show();
							$("#updateT").hide();
							$("#updateButton").hide();
							clearData();
							allData();
		
				  		}
				});
			  }
			})
		}
	//End Delete Data

</script>
</body>
</html>