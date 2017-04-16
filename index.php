<?php
   //include('session.php');
?>

<html>
<head>
	<meta charset="UTF-8">
  	<title>Datatable</title>
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  	<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
  	<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
</head>
<body>
	<div style="position: relative; float: right;">
		<?php echo 'User: <b>' . $_SESSION['logged_in_user'] . '</b>'; ?>
		<input type="button" onclick="window.location='logout.php';" value="Logout" style="padding-left: 20px;"/>
	</div>
	<div id="dt-div" style="width: 900px; border: 1px solid black; padding: 5px; position: relative; top: 15px; left: 50%; transform: translateX(-50%);">
		<form>
	  		<select id="table_options" class="form" name="db_table" onchange="update_datatables()" style="position: relative; float: left;">
		    		<option value="fish" selected>Fish (default)</option>
		    		<option value="bugs">Bugs</option>
		    		<option value="deep_sea_creatures">Deep Sea Creatures</option>
	  		</select>
		</form>
		<table id="example" class="display" width="100%"></table>
	</div>
	<div id="debug"></div>
	<script>

		var datatables_array = new Array();

		

		function update_caught_status(_db_table, _number, _caught, _obj_checkbox) {
			console.log("update_caught_status");
			// lock checkbox until request is completed
			_obj_checkbox.attr('disabled', true);
			$.post('update_caught.php', 
				{ 
					'db_table': _db_table, 
					'id_number': _number,
					'caught': _caught
				}
			).done(function() {
				// unlock checkbox now that query has completed
				_obj_checkbox.prop('disabled', false);
			});
		}





		function make_datatable(db_table, json_data) {

			switch(db_table) {
				case 'fish':
					var col_opt = [
	                        { "sTitle": "Number", "sClass": "dt-right"},
	                        { "sTitle": "Name", "sClass": "dt-left" },
	                        { "sTitle": "Price", "sClass": "dt-right" },
	                        { "sTitle": "Shadow", "sClass": "dt-left" },
	                        { "sTitle": "Location", "sClass": "dt-left" },
	                        { "sTitle": "Caught?", "sClass": "dt-center", 'bSortable' : false}
	                ];
	                break;
	            case 'bugs':
					var col_opt = [
	                        { "sTitle": "Number", "sClass": "dt-right"},
	                        { "sTitle": "Name", "sClass": "dt-left" },
	                        { "sTitle": "Price", "sClass": "dt-right" },
	                        { "sTitle": "Caught?", "sClass": "dt-center", 'bSortable' : false}
	                ];
	                break;
	            case 'deep_sea_creatures':
					var col_opt = [
	                        { "sTitle": "Number", "sClass": "dt-right"},
	                        { "sTitle": "Name", "sClass": "dt-left" },
	                        { "sTitle": "Price", "sClass": "dt-right" },
	                        { "sTitle": "Shadow", "sClass": "dt-left" },
	                        { "sTitle": "Location", "sClass": "dt-left" },
	                        { "sTitle": "Caught?", "sClass": "dt-center", 'bSortable' : false}
	                ];
	                break;
	            default:
	            	console.log(db_table + ' is not a legitimate table name in function init_datatable.');

			}

			var datatable_element = document.createElement("table");
			var att = document.createAttribute("id");
			att.value = db_table;
			datatable_element.setAttributeNode(att);
			att = document.createAttribute("class");
			att.value = 'display';
			datatable_element.setAttributeNode(att);

			$('#dt-div').append(datatable_element);

			var datatable = $('#' + db_table);

			datatable.DataTable( {
		        data: json_data,
		        //pageLength: 10,
		        destroy: true,
		        //bLengthChange: false,
		        aoColumns: col_opt,
		        scrollY:        '70vh',
		        scrollCollapse: true,
		        paging:         false,
		        fnDrawCallback: function( oSettings ) {
			    	// update cuaght values for checkbox changes
				    $(':checkbox').change(function() {
			    		if (this.checked) {
			    			update_caught_status(db_table, $(this).data("number"), 1, $(this));
			    			console.log(json_data[$(this).data("number") - 1][1] + " updated to caught!");
			    		} else {
			    			update_caught_status(db_table, $(this).data("number"), 0, $(this));
			    			console.log(json_data[$(this).data("number") - 1][1] + " updated to uncaught!");
			    		}
					});
			    }
		    });

		    datatables_array[db_table] = datatable;
		}


		function show_selected_table(db_table, json_data) {
			for (var datatable in datatables_array) {
				if (datatable == db_table) {
					datatables_array[datatable].parents('div.dataTables_wrapper').first().show();
				} else {
					datatables_array[datatable].parents('div.dataTables_wrapper').first().hide();
				}
			}

			
		}


		function update_datatables() {
			var db_table = document.getElementById("table_options").value;

			if (datatables_array[db_table]) {
				show_selected_table(db_table);
			} else {
				$.post('table_json.php', 
					{ 
						'db_table': db_table
					}, 
					function(data) {
						make_datatable(db_table, data);
						show_selected_table(db_table, data);
					}, 
					"json"
				);
			}
		}
		
		$(document).ready(function() {
			update_datatables();
		});


	</script>
</body>
</html>