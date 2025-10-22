<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Quest Tracker">
    <meta name="author" content="ShinDarth">
    <title>TC Quest Tracker</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="css/style.css">
  </head>
  <body>
    <div class="container py-4">
      <p class="h2 text-center"><img src="img/trinitycore.png" alt="TrinityCore">TrinityCore Quest Tracker</p>
      <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" id="abandonedOnlyToggle">
          <label class="form-check-label" for="abandonedOnlyToggle">Show only abandoned quests (never completed)</label>
        </div>
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" id="darkModeToggle">
          <label class="form-check-label" for="darkModeToggle">Dark Mode</label>
        </div>
      </div>
      <hr>
      <br>
      <table id="table1" class="table align-middle table-striped">
        <thead>
            <tr>
              <th>Quest ID</th>
              <th>Quest Name</th>
              <th>Abandoned</th>
              <th>Completed</th>
              <th>Last Abandoned</th>
              <th>Last Completed</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
      <br>
      <hr>
      <h6 class="text-center">Coded by <a href="https://www.github.com/ShinDarth">ShinDarth</a>&nbsp;<iframe style="vertical-align: middle;" src="https://ghbtns.com/github-btn.html?user=ShinDarth&repo=TC-Quest-Tracker&type=watch&count=true" width="46" height="20"></iframe></h6>
      <h6 class="text-center">Modified by <a href="https://www.github.com/masterking32">MasterkinG32</a>&nbsp;<iframe style="vertical-align: middle;" src="https://ghbtns.com/github-btn.html?user=masterking32&repo=TC-Quest-Tracker&type=watch&count=true" width="46" height="20"></iframe></h6>
      <a class="d-none d-md-block" href="https://github.com/TrinityCore/TC-Quest-Tracker"><img style="position: absolute; top: 0; right: 0; border: 0;" src="img/forkme_right_darkblue_121621.png" alt="Fork me on GitHub"></a>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://aowow.trinitycore.info/static/widgets/power.js"></script><script>var aowow_tooltips = { "colorlinks": true, "iconizelinks": true, "renamelinks": true }</script>
    <script src="js/dark-mode.js"></script>
    <script>
    let dataTable;

	  $(document).ready(function() {
		  dataTable = $('#table1').DataTable( {
			"processing": true,
			"serverSide": true,
			"ajax": {
                url: "./server_processing.php",
                type: "GET",
                data: function (d) {
                    d.abandoned_only = $('#abandonedOnlyToggle').is(':checked') ? 1 : 0;
                }
            },
			"columnDefs": [
                { className: "text-center", targets: [ 0, 1, 2, 3, 4, 5 ] }
            ]
		} );

        $('#abandonedOnlyToggle').on('change', function() {
            dataTable.ajax.reload();
        });
	} );
    </script>
  </body>
</html>
