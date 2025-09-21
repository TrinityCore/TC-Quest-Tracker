<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Quest Tracker">
  <title>TC Quest Tracker (Reborn)</title>
  <meta name="generator" content="MasterkinG CMS">
  <link href="css/app.css" rel="stylesheet">
</head>

<body>
  <div class="mx-auto p-4 w-full mt-10">
    <div class="bg-base-300 card shadow-xl">
      <div class="card-body text-center">
        <h2 class="card-title mx-auto text-center">
          <img src="img/trinitycore.png" alt="trinitycore">
          TrinityCore Quest Tracker (Reborn)
        </h2>
        <div>
          <table class="table w-full" id="data-table">
            <thead>
              <th class=" text-center">Quest ID</th>
              <th class="text-center">Quest Name</th>
              <th class="text-center">Abandoned</th>
              <th class="text-center">Completed</th>
              <th class="text-center">Last Abandoned</th>
              <th class="text-center">Last Completed</th>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
        <div>
          Created by <a href="https://github.com/MasterkinG32" class="text-primary" target="_blank">MasterkinG32</a>
        </div>
      </div>
    </div>
  </div>
  <script src="js/simple-datatables.js" type="text/javascript"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {

      const table_document = document.getElementById("data-table");

      if (table_document) {
        new simpleDatatables.DataTable(table_document, {
          searchable: true,
          sortable: true,
          perPageSelect: [10, 15, 20, 25, 50, 100],
          perPage: 20,
          classes: {},
        });
      }
    });
  </script>
</body>

</html>