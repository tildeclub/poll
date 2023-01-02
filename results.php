<?php

// Connect to the SQLite database
$db = new PDO('sqlite:poll.db');

// Read the poll options from the text file, skipping the first line
$options = file('poll_options.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
array_shift($options);

// Get the total number of votes
$total_votes = $db->query("SELECT COUNT(*) FROM poll_votes")->fetchColumn();

// Prepare the data for the chart
$data = [['Option', 'Votes']];
foreach ($options as $id => $option) {
  // Get the vote count for this option
  $vote_count = $db->query("SELECT COUNT(*) FROM poll_votes WHERE option_id=$id")->fetchColumn();

  // Add the data for this option to the chart data
  $data[] = [$option, $vote_count];
}

?>
<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable(<?php echo json_encode($data); ?>);

        var options = {
          title: 'Poll Results'
        };

        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <div id="chart_div"></div>
    <br><br>
    <button onclick="location.href='index.php'">Return to Poll</button>
  </body>
</html>
