<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Quest Tracker">
    <meta name="author" content="ShinDarth">
    <title>TC Quest Tracker</title>
	
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="container">
      <p class="h2 text-center"><img src="img/trinitycore.png" alt="TrinityCore">TrinityCore Quest Tracker</p>
      <hr>
      <br>
      <table id="table1" class="display table text-center" data-sortable>
        <thead>
          <th class="text-center">Quest ID</th>
          <th class="text-center">Quest Name</th>
          <th class="text-center">Abandoned</th>
          <th class="text-center">Completed</th>
          <th class="text-center">Last Abandoned</th>
          <th class="text-center">Last Completed</th>
        </thead>
        <tbody>
        
        </tbody>
      </table>
      <br>
      <hr>
      <p class="h4 text-center">Coded by <a href="http://www.github.com/ShinDarth">ShinDarth</a>&nbsp;<iframe style="vertical-align: middle;" src="http://ghbtns.com/github-btn.html?user=ShinDarth&repo=TC-Quest-Tracker&type=watch&count=true" allowtransparency="true" frameborder="0" scrolling="0" width="110" height="20"></iframe></p>
      <p class="h4 text-center">Modified by <a href="http://www.github.com/masterking32">MasterkinG32</a>&nbsp;<iframe style="vertical-align: middle;" src="http://ghbtns.com/github-btn.html?user=masterking32&repo=TC-Quest-Tracker&type=watch&count=true" allowtransparency="true" frameborder="0" scrolling="0" width="110" height="20"></iframe></p>
      <a href="https://github.com/masterking32/TC-Quest-Tracker"><img style="position: absolute; top: 0; right: 0; border: 0;" src="https://camo.githubusercontent.com/38ef81f8aca64bb9a64448d0d70f1308ef5341ab/68747470733a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f72696768745f6461726b626c75655f3132313632312e706e67" alt="Fork me on GitHub" data-canonical-src="https://s3.amazonaws.com/github/ribbons/forkme_right_darkblue_121621.png"></a>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>
    <script src="js/sortable.min.js"></script>
    <script>

	  $(document).ready(function() {
		$('#table1').DataTable( {
			"processing": true,
			"serverSide": true,
			"ajax": "./server_processing.php"
		} );
	} );
    </script>
    </script>
  </body>
</html>
