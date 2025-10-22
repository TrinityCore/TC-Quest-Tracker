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
    <div class="container py-5">
      <p class="h2 text-center"><img src="img/trinitycore.png" alt="TrinityCore">TrinityCore Quest Tracker</p>
      <div class="form-check form-switch ms-4">
        <input class="form-check-input" type="checkbox" id="darkModeToggle">
        <label class="form-check-label" for="darkModeToggle">Dark Mode</label>
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
    <script>
	  $(document).ready(function() {
		$('#table1').DataTable( {
			"processing": true,
			"serverSide": true,
			"ajax": "./server_processing.php",
			"columnDefs": [
                { className: "text-center", targets: [ 0, 1, 2, 3, 4, 5 ] }
            ]
		} );
	} );
    </script>
    <script>
    // Dark Mode
    const toggle = $('#darkModeToggle');
    const body = $('body');
    const storageKey = 'themePreference';
    const darkTheme = 'dark';
    const lightTheme = 'light';

    function applyTheme(theme) {
        if (theme === darkTheme) {
            body.attr('data-bs-theme', darkTheme);
            toggle.prop('checked', true);
        } else {
            body.attr('data-bs-theme', lightTheme);
            toggle.prop('checked', false);
        }
    }

    let currentTheme = localStorage.getItem(storageKey) || lightTheme;
    applyTheme(currentTheme);

    toggle.on('change', function() {
        if (toggle.is(':checked')) {
            applyTheme(darkTheme);
            localStorage.setItem(storageKey, darkTheme);
        } else {
            applyTheme(lightTheme);
            localStorage.setItem(storageKey, lightTheme);
        }
    });
    </script>
  </body>
</html>
