<?php

// Connect to the SQLite database
$db = new PDO('sqlite:poll.db');

// Read the poll options from the text file, skipping the first line
$options = file('poll_options.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
array_shift($options);

// Get the total number of votes
$total_votes = $db->query("SELECT COUNT(*) FROM poll_votes")->fetchColumn();

// Display the results
foreach ($options as $id => $option) {
  // Get the vote count for this option
  $vote_count = $db->query("SELECT COUNT(*) FROM poll_votes WHERE option_id=$id")->fetchColumn();

  // Calculate the percentage of votes for this option
  $percentage = round(($vote_count / $total_votes) * 100);

  // Display the results
  echo "$option: $percentage% ($vote_count votes)<br>";
}

?>
