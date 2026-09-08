const pageStyles = getComputedStyle(document.documentElement);
const textColor = pageStyles.getPropertyValue("--text-muted").trim() || "#6B7280";
const gridColor = pageStyles.getPropertyValue("--border").trim() || "#E4E8EF";
const surfaceColor = pageStyles.getPropertyValue("--surface").trim() || "#FFFFFF";

function setChartDefaults() {
  Chart.defaults.font.family = "Inter, system-ui, -apple-system, 'Segoe UI', sans-serif";
  Chart.defaults.font.size = 11;
  Chart.defaults.color = textColor;
  Chart.defaults.borderColor = gridColor;
  Chart.defaults.maintainAspectRatio = false;
  Chart.defaults.plugins.legend.labels.usePointStyle = true;
  Chart.defaults.plugins.legend.labels.boxWidth = 8;
  Chart.defaults.plugins.legend.labels.boxHeight = 8;
  Chart.defaults.plugins.legend.labels.padding = 14;
  Chart.defaults.plugins.tooltip.backgroundColor = "rgba(26,35,51,.94)";
  Chart.defaults.plugins.tooltip.padding = 10;
  Chart.defaults.plugins.tooltip.cornerRadius = 8;
}

function fadeColor(hex, opacity) {
  const red = parseInt(hex.substring(1, 3), 16);
  const green = parseInt(hex.substring(3, 5), 16);
  const blue = parseInt(hex.substring(5, 7), 16);
  return "rgba(" + red + "," + green + "," + blue + "," + opacity + ")";
}

function toDataset(chart, series) {
  if (chart.type === "doughnut") {
    return {
      data: series.data,
      backgroundColor: series.colors,
      borderColor: surfaceColor,
      borderWidth: 2,
      hoverOffset: 6,
    };
  }

  if (chart.type === "line") {
    return {
      label: series.label,
      data: series.data,
      borderColor: series.color,
      backgroundColor: fadeColor(series.color, 0.12),
      fill: true,
      tension: 0.35,
      borderWidth: 2,
      pointRadius: chart.labels.length <= 12 ? 2 : 0,
      pointHoverRadius: 4,
      pointBackgroundColor: series.color,
    };
  }

  return {
    label: series.label,
    data: series.data,
    backgroundColor: series.colors || series.color,
    borderRadius: 6,
    borderSkipped: false,
    maxBarThickness: 28,
  };
}

function toOptions(chart) {
  if (chart.type === "doughnut") {
    return { cutout: "62%", plugins: { legend: { position: "bottom" } } };
  }

  const prefix = chart.prefix || "";
  const suffix = chart.suffix || "";
  const valueAxis = {
    beginAtZero: chart.fromZero !== false,
    stacked: Boolean(chart.stacked),
    border: { display: false },
    grid: { color: gridColor },
    ticks: { maxTicksLimit: 6, callback: (value) => prefix + value + suffix },
  };
  const labelAxis = {
    stacked: Boolean(chart.stacked),
    grid: { display: false },
    ticks: { maxRotation: 0, autoSkipPadding: 12 },
  };

  let legend = { display: false };
  if (chart.series.length > 1) legend = { position: "top", align: "end" };

  if (chart.horizontal) {
    return { indexAxis: "y", plugins: { legend: legend }, scales: { x: valueAxis, y: labelAxis } };
  }
  return { plugins: { legend: legend }, scales: { x: labelAxis, y: valueAxis } };
}

const chartDataTag = document.getElementById("chart-data");

if (window.Chart && chartDataTag) {
  setChartDefaults();
  const charts = JSON.parse(chartDataTag.textContent);

  document.querySelectorAll("canvas[data-chart]").forEach((canvas) => {
    const chart = charts[canvas.dataset.chart];
    if (!chart) return;

    new Chart(canvas, {
      type: chart.type,
      data: {
        labels: chart.labels,
        datasets: chart.series.map((series) => toDataset(chart, series)),
      },
      options: toOptions(chart),
    });
  });
}
