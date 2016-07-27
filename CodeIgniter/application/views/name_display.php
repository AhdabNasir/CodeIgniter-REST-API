<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to Admin</title>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
       <script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.5.2/underscore-min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/backbone.js/1.0.0/backbone-min.js"></script>

</head>
<body>

<div class="container">
	<h1>Welcome to Admin!</h1>

<div id="data">
<table>
   <?php foreach ($names as $row) { ?>
   
   <tr>
      <td><?=$row->personID?></td>
      <td><?=$row->name?></td>
      <td><?=$row->address?></td>
      <td><?=$row->telephone?></td>
    </tr>
    
    <?php } ?>
</table>
</div>

<br>

<p id="message"></p>
<p id="createmsg"></p>

<br> <br><br>

<h3>Create Persons</h3>

   <form>
   
   <label for='Name'> Name </label>
   <input type='text' name='name' id='name' size='30' /> <br>

   <label for='Address'> Address </label>
   <input type='text' name='address' id='address' size='30' /> <br>
   
      <label for='Telephone'> Telephone </label>
   <input type='text' name='telephone' id='telephone' size='30' /> <br>
   
   <input type="submit" value="Create" id="create" />
   
   </form>
   
   <br><br>
   
   <form>
     <label for="edit"> Type in the id to delete/edit</label>
       <input type="text" name="personID" id="personID" size="10" /> <br>
       
          <input type="submit" value="Delete" id="delete" />
             <input type="submit" value="Edit" id="edit" />
   </form>
   
   <br><br><br>
   
  <div id="editBox" style="display: none;"> 
   <form>
   
   <input type="hidden" name="personID" id="personID" size="20" /> <br>
   
     <label for="editname">Edit Name</label>
      <input type="text" name="editname" id="editname" size="30" /> <br>
      
      <label for="editname">Edit Address</label>
      <input type="text" name="editaddress" id="editaddress" size="30" /> <br>
      
      <label for="editname">Edit Telephone</label>
      <input type="text" name="edittelephone" id="edittelephone" size="30" /> <br>
      
      <input type="submit" value="Update" id="update">
   
   </form>
   
   </div>
   
   
  <script>
  
  $(document).ready(function() {
	  
	  $("#create").click(function(event) {
		  event.preventDefault();
		var name = $("input#name").val();  
	    var address = $("input#address").val(); 
	    var telephone = $("input#telephone").val(); 
	$.ajax({
		method: "POST",
		url: "<?php echo base_url(); ?>index.php/People/person",	
		dataType: 'JSON',
		data: {name: name, address: address, telephone: telephone},
		
		success: function(data) {
			console.log(name, address, telephone);
			$("#data").load(location.href + " #data");
			$("input#name").val(""); 
			$("input#address").val(""); 
			$("input#telephone").val("");  
		}
	});
	  });
  });
  
  
  
  
  
  $(document).ready(function() {
	  $("#delete").click(function(event) {
		  event.preventDefault();
		var personID = $("input#personID").val();  
	$.ajax({
		method: "GET",
		url: "<?php echo base_url(); ?>index.php/People/person",	
		dataType: 'JSON',
		data: {personID: personID},
		success: function(data) {
			console.log(personID);
			$("#data").load(location.href + " #data");
			$("#message").html("You have successfully deleted number " + personID + " Thank you");
			$("#message").show().fadeOut(3000);
			$("input#personID").val("");  
		}
	});
	  });
  });
  
  
  
   $(document).ready(function() {
	  $("#edit").click(function(event) {
		  event.preventDefault();
		var personID = $("input#personID").val();  
	$.ajax({
		method: "GET",
		url: "<?php echo base_url(); ?>index.php/People/user",	
		dataType: 'JSON',
		data: {personID: personID},
		
		success: function(data) {
			
			$.each(data,function(personID, name, address, telephone) {
			
			console.log(personID, name, address, telephone);
			$("input#personID").val(personID); 
			$("#editBox").show();
			$("input#editname").val(name[0]);
			$("input#editaddress").val(name[1]);
			$("input#edittelephone").val(name[2]);
			});
		}
	});
	  });
  });
  
  
  
   $(document).ready(function() {
	  
	  $("#update").click(function(event) {
		  event.preventDefault();
		 var personID = $("input#personID").val();
		var name = $("input#editname").val();  
	    var address = $("input#editaddress").val(); 
	    var telephone = $("input#edittelephone").val(); 
	$.ajax({
		method: "POST",
		url: "<?php echo base_url(); ?>index.php/People/user",	
		dataType: 'JSON',
		data: {personID: personID, name: name, address: address, telephone: telephone},
		
		success: function(data) {
			console.log(personID, name, address, telephone);
			$("#data").load(location.href + " #data");
			$("#message").html("You have successfully updated " + name + " Thank you");
			$("#message").show().fadeOut(3000);
			$("#editBox").hide();
		}
	});
	  });
  });
  
  
     $(document).ready(function() {
		 
		var Create = Backbone.Model.extend({
			url: function () {
				var link = "<?php echo base_url(); ?>index.php/People/person?name=" + this.get("name");
				return link;
			},
			defaults: {
				name: null,
				address: null,
				telephone: null }
		});
		
		var createModel = new Create();
		
		var DisplayView = Backbone.View.extend({
			el: ".container", 
			model: createModel,
			initialize: function () {
				this.listenTo(this.model,"sync change",this.gotdata);
			},
			
			events: {
				"click #create" : "getdata"
			},
			
			getdata: function (event) {
				var name = $('input#name').val();
				var address = $('input#address').val();
				this.model.set({name: name, address: address});
				this.model.fetch();
			},
			
			gotdata: function () {
				$('#createmsg').html('Name ' + this.model.get('name') + ' and address ' + this.model.get('address') + ' has been created').show().fadeOut(5000);
			}
		});
		
		var displayView = new DisplayView();
		
	 });
  
  
  
  
  </script>



</div>

</body>
</html>