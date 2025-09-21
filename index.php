<?php
require_once 'config.php';
?>
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
  <div class="mx-auto p-4 w-2xl max-w-full mt-10">
    <div class="bg-base-300 card shadow-xl">
      <div class="card-body text-center">
        <h2 class="card-title mx-auto text-center">
          <img src="img/trinitycore.png" alt="trinitycore">
          TrinityCore Quest Tracker (Reborn)
        </h2>
        <div>
          <input type="text" id="search" class="mt-5 input input-bordered w-full mb-4 max-w-md " placeholder="Search quests..." />
          <div id="loading" class="hidden text-center py-4">
            <span class="loading loading-spinner loading-lg"></span>
            <p class="mt-2">Loading quests...</p>
          </div>
          <div id="error-message" class="hidden alert alert-error mb-4">
            <span id="error-text">An error occurred while loading data.</span>
          </div>
          <table class="table w-full" id="data-table">
            <thead>
              <th class="text-center">Quest Name</th>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
        <div class="flex justify-between items-center mt-4">
          <button id="prev" class="btn btn-primary">Previous</button>
          <span id="pageInfo" class="mx-2"></span>
          <button id="next" class="btn btn-primary">Next</button>
        </div>
        <div>
          Created by <a href="https://github.com/MasterkinG32" class="text-primary" target="_blank">MasterkinG32</a>
        </div>
      </div>
    </div>
  </div>
  <dialog id="quest_detail_modal" class="modal">
    <div class="modal-box">
      <h3 class="text-lg font-bold">Quest Detail</h3>
      <!-- Loading -->
      <div id="quest-detail-loading" class="text-center py-4">
        <span class="loading loading-spinner loading-lg"></span>
        <p class="mt-2">Loading quest details...</p>
      </div>
      <div id="quest-detail-content" class="hidden">
        <!-- Quest details will be loaded here -->
      </div>
      <div class="modal-action">
        <form method="dialog">
          <button class="btn">Close</button>
        </form>
      </div>
    </div>
  </dialog>
  <script src="js/simple-datatables.js" type="text/javascript"></script>

  <script>
    const PAGE_SIZE = 10;
    const ENDPOINT = '/quest_api.php';

    const searchEl = document.getElementById('search');
    const prevBtn = document.getElementById('prev');
    const nextBtn = document.getElementById('next');
    const pageInfo = document.getElementById('pageInfo');
    const tableEl = document.getElementById('data-table');
    const loadingEl = document.getElementById('loading');
    const errorEl = document.getElementById('error-message');
    const errorText = document.getElementById('error-text');

    let page = 1;
    let total = 0;
    let dt = null;

    function debounce(fn, delay = 300) {
      let t = null;
      return (...args) => {
        clearTimeout(t);
        t = setTimeout(() => fn(...args), delay);
      };
    }

    async function fetchPage(query, pageNum) {
      const url = new URL(ENDPOINT, "<?php echo domain; ?>");
      url.searchParams.set('query', query || '');
      url.searchParams.set('page', String(pageNum));
      url.searchParams.set('pageSize', String(PAGE_SIZE));

      url.searchParams.set('_t', Date.now());

      const res = await fetch(url);
      if (!res.ok) {
        const errorData = await res.json().catch(() => ({}));
        throw new Error(errorData.error || `HTTP ${res.status}: ${res.statusText}`);
      }
      return res.json();
    }

    function load_quest(quest_id) {
      const modal = document.getElementById('quest_detail_modal');
      const loading = document.getElementById('quest-detail-loading');
      loading.classList.remove('hidden');
      const content = document.getElementById('quest-detail-content');
      content.classList.add('hidden');
      content.innerHTML = '';
      if (typeof modal.showModal === "function") {
        modal.showModal();
        fetch(new URL(`/quest_info.php?id=${quest_id}&_t=${Date.now()}`, "<?php echo domain; ?>"))
          .then(res => {
            if (!res.ok) {
              throw new Error(`HTTP ${res.status}: ${res.statusText}`);
            }
            return res.json();
          })
          .then(data => {
            loading.classList.add('hidden');
            content.classList.remove('hidden');
            content.innerHTML = `
            <div class="divider my-4"></div>
              <h4 class="text-xl font-bold mb-2">
              <a href="<?php echo quest_url; ?>${data.quest_id}" target="_blank" class="text-primary">${data.quest_title}</a>
               (ID: ${data.quest_id})
               </h4>
              <p><strong>Accepted:</strong> ${data.accept_count}</p>
              <p><strong>Completed:</strong> ${data.complete_count}</p>
              <p><strong>Abandoned:</strong> ${data.abandon_count}</p>
              <p><strong>First Completed:</strong> ${data.first_completed ? data.first_completed : 'N/A'}</p>
              <p><strong>Last Completed:</strong> ${data.last_completed ? data.last_completed : 'N/A'}</p>
              <div class="divider my-4"></div>
              <h4 class="text-lg font-bold mb-2">First Players to Complete</h4>
              <ul class="list-disc list-inside mb-4">
                ${data.first_players.length > 0 ? data.first_players.map(p => `<li>${p.player_name}</li>`).join('') : '<li class="text-gray-500">No players found</li>'}
              </ul>
              <h4 class="text-lg font-bold mb-2">Last Players to Complete</h4>
              <ul class="list-disc list-inside mb-4">
                ${data.last_players.length > 0 ? data.last_players.map(p => `<li>${p.player_name}</li>`).join('') : '<li class="text-gray-500">No players found</li>'}
              </ul>

            `;
          })
          .catch(err => {
            loading.classList.add('hidden');
            content.classList.remove('hidden');
            content.innerHTML = `<p class="text-red-500">Failed to load quest details: ${err.message}</p>`;
          });

      } else {
        alert("The <dialog> API is not supported by this browser");
      }
    }

    function renderTable(items) {
      hideError();
      if (dt) {
        try {
          dt.destroy();
        } catch (e) {}
        dt = null;
      }

      tableEl.innerHTML = `
        <thead>
          <tr>
            <th class="text-center">Quest Name</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      `;

      if (items.quests && items.quests.length > 0) {
        const tbody = tableEl.querySelector('tbody');
        const questRows = items.quests.map(q =>
          `<tr><td class="text-center"><button onclick="load_quest('${q.quest_id}')">${q.quest_title}</button></td></tr>`
        ).join('');

        tbody.innerHTML = questRows;
      } else {
        const tbody = tableEl.querySelector('tbody');
        tbody.innerHTML = '<tr><td class="text-center text-gray-500">No quests found</td></tr>';
      }

      total = items.total || 0;
      updatePagination();
    }

    function updatePagination() {
      const totalPages = Math.ceil(total / PAGE_SIZE);
      pageInfo.textContent = `Page ${page} of ${totalPages} (${total} quests)`;

      prevBtn.disabled = page <= 1;
      nextBtn.disabled = page >= totalPages;
    }

    function showLoading() {
      loadingEl.classList.remove('hidden');
      errorEl.classList.add('hidden');
      tableEl.style.opacity = '0.5';
    }

    function hideLoading() {
      loadingEl.classList.add('hidden');
      tableEl.style.opacity = '1';
    }

    function showError(message) {
      hideLoading();
      errorText.textContent = message;
      errorEl.classList.remove('hidden');
    }

    function hideError() {
      errorEl.classList.add('hidden');
    }

    async function loadPage(pageNum, query = '') {
      showLoading();
      hideError();

      try {
        const data = await fetchPage(query, pageNum);

        page = pageNum;
        renderTable(data);
        hideLoading();
      } catch (e) {
        showError(`Failed to load quests: ${e.message}`);

        if (dt) {
          try {
            dt.destroy();
          } catch (destroyError) {
            console.log('Error destroying DataTable on error:', destroyError);
          }
          dt = null;
        }

        tableEl.innerHTML = `
          <thead>
            <tr>
              <th class="text-center">Quest Name</th>
            </tr>
          </thead>
          <tbody>
            <tr><td class="text-center text-gray-500">No data available</td></tr>
          </tbody>
        `;
      }
    }

    const runSearch = debounce(async (q) => {
      page = 1;
      await loadPage(page, q);
    }, 350);

    prevBtn.addEventListener('click', () => {
      if (page > 1) {
        loadPage(page - 1, searchEl.value);
      }
    });

    nextBtn.addEventListener('click', () => {
      const totalPages = Math.ceil(total / PAGE_SIZE);
      if (page < totalPages) {
        loadPage(page + 1, searchEl.value);
      }
    });

    searchEl.addEventListener('input', (e) => runSearch(e.target.value));
    document.addEventListener('DOMContentLoaded', function() {
      setTimeout(() => {
        runSearch('');
      }, 100);
    });

    if (document.readyState === 'loading') {} else {
      setTimeout(() => {
        runSearch('');
      }, 100);
    }
  </script>
</body>

</html>