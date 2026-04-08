document.addEventListener('DOMContentLoaded', function () {

  // ===== LIVE SEARCH =====
  const searchInput = document.getElementById('liveSearch');
  const tableBody   = document.getElementById('tableBody');
  const searchCount = document.getElementById('searchCount');

  if (searchInput) {
    searchInput.addEventListener('input', function(){
      const keyword = this.value.toLowerCase().trim();
      const rows    = tableBody.querySelectorAll('tr:not(#emptyRow)');
      let visible   = 0;
      const old = tableBody.querySelector('#noResult');
      if(old) old.remove();

      rows.forEach(function(row){
        const p = row.cells[1] ? row.cells[1].textContent.toLowerCase() : '';
        const k = row.cells[3] ? row.cells[3].textContent.toLowerCase() : '';
        const m = p.includes(keyword) || k.includes(keyword);
        row.style.display = m ? '' : 'none';
        if(m) visible++;
      });

      let no = 1;
      rows.forEach(function(row){
        if(row.style.display !== 'none' && row.cells[0]) row.cells[0].textContent = no++;
      });

      if(visible === 0 && keyword !== ''){
        const er = document.createElement('tr');
        er.id = 'noResult';
        er.innerHTML = '<td colspan="6" style="text-align:center;color:rgba(255,255,255,0.22);padding:26px;font-size:13px;">Tidak ada data untuk "<strong style=\'color:rgba(255,255,255,0.45)\'>' + keyword + '</strong>"</td>';
        tableBody.appendChild(er);
        searchCount.textContent = '';
      } else if(keyword !== ''){
        searchCount.textContent = visible + ' data ditemukan';
      } else {
        searchCount.textContent = '';
      }
    });
  }

  // ===== CHART =====
  const chartEl = document.getElementById('chart');
  if (chartEl) {
    const labels  = JSON.parse(chartEl.dataset.labels || '[]');
    const values  = JSON.parse(chartEl.dataset.values || '[]');

    new Chart(chartEl, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          label: 'Total Panen',
          data: values,
          backgroundColor: 'rgba(76,175,80,0.22)',
          borderColor: '#4CAF50',
          borderWidth: 2,
          borderRadius: 8,
          borderSkipped: false,
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { labels: { color: 'rgba(255,255,255,0.45)', font: { family: 'DM Sans', size: 12 } } },
          tooltip: {
            backgroundColor: 'rgba(10,26,14,0.96)',
            borderColor: 'rgba(76,175,80,0.28)',
            borderWidth: 1,
            titleColor: '#fff',
            bodyColor: 'rgba(255,255,255,0.55)',
            titleFont: { family: 'Playfair Display', size: 14 },
            bodyFont:  { family: 'DM Sans', size: 12 },
          }
        },
        scales: {
          x: { ticks: { color: 'rgba(255,255,255,0.38)', font: { family: 'DM Sans', size: 11 } }, grid: { color: 'rgba(255,255,255,0.035)' } },
          y: { ticks: { color: 'rgba(255,255,255,0.38)', font: { family: 'DM Sans', size: 11 } }, grid: { color: 'rgba(255,255,255,0.035)' } }
        }
      }
    });
  }

  // ===== DELETE CONFIRM (CUSTOM MODAL) =====
  document.querySelectorAll('.form-delete').forEach(function(form) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();

      showDeleteModal(() => {
        form.submit();
      });
    });
  });

  function showDeleteModal(onConfirm) {
    const modal = document.getElementById('deleteModal');
    modal.classList.add('show');

    document.getElementById('confirmDelete').onclick = function () {
      modal.classList.remove('show');
      onConfirm();
    };

    document.getElementById('cancelDelete').onclick = function () {
      modal.classList.remove('show');
    };
  }
});