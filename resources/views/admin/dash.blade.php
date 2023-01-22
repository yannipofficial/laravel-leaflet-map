@extends('layouts.admin') 

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<div class="container-fluid">
  <div class="container">
    <div class="row">
      <div class="col">
        <canvas id="myChart" width="400" height="400"></canvas>
      </div>
      <div class="col">
        <canvas id="myChart1" width="400" height="400"></canvas>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <canvas id="myChart2" width="400" height="400"></canvas>
      </div>
      <div class="col">
        <canvas id="myChart3" width="400" height="400"></canvas>
      </div>
    </div>
  </div>
</div>

<script>
  const ctx = document.getElementById('myChart1').getContext('2d');

  const myChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
      datasets: [
        {
          label: '# of Votes',
          data: [12, 19, 3, 5, 2, 3],
          backgroundColor: ['rgba(255, 99, 132, 0.2)'],
          borderColor: ['rgba(255, 99, 132, 1)'],
          borderWidth: 1,
        },
      ],
    },
    options: {
      scales: {
        y: {
          beginAtZero: true,
        },
      },
    },
  });
</script>
@endsection
