import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', () => {
  const canvas = document.getElementById('lineChart');
  if (!canvas) return;

  const ctx = canvas.getContext('2d');
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
      datasets: [
        {
          label: 'Ventes',
          data: [120, 150, 180, 170, 210, 240],
          fill: true,
          tension: 0.4,
          backgroundColor: 'rgba(99, 102, 241, 0.1)',
          borderColor: '#6366f1',
          pointBackgroundColor: '#6366f1',
          pointBorderColor: '#fff'
        },
        {
          label: 'Commandes',
          data: [80, 110, 130, 120, 160, 180],
          fill: false,
          tension: 0.4,
          borderColor: '#facc15',
          pointBackgroundColor: '#facc15',
          pointBorderColor: '#fff'
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false
    }
  });
});
