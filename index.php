<script src='https://www.google.com/recaptcha/api.js'></script>

<?php

// Connect to the SQLite database
$db = new PDO('sqlite:poll.db');

// Read the expiry date and poll options from the text file
$lines = file('poll_options.txt', FILE_IGNORE_NEW_LINES);
$expiry_date = strtotime($lines[0]);
$poll_options = array_slice($lines, 1);

// Read the poll question from the text file
$question = file_get_contents('question.txt');

// Check if the poll has expired
$current_date = time();
if ($current_date > $expiry_date) {
  // Poll has expired
  echo "This poll has expired.";
  exit;
}

// Display the expiry date of the poll
echo "This poll ends at: " . date('l, F j, Y', $expiry_date);

// Display the poll question
echo "<h1>$question</h1>";

// Check if the form has been submitted
if (isset($_POST['submit'])) {
  // Form has been submitted
  // Verify the reCaptcha response
  $url = 'https://www.google.com/recaptcha/api/siteverify';
  $data = array('secret' => '6LdClsMjAAAAAEaoHxGRckp38hNpZJRU8oR8MNzf', 'response' => $_POST['g-recaptcha-response']);
  $options = array(
    'http' => array(
      'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
      'method'  => 'POST',
      'content' => http_build_query($data),
    ),
  );
  $context  = stream_context_create($options);
  $response = file_get_contents($url, false, $context);
  $result = json_decode($response);
  if ($result->success) {
    // reCaptcha was successful
    // Save the vote to the database
    $stmt = $db->prepare("INSERT INTO poll_votes (option_id) VALUES (?)");
    $stmt->execute([$_POST['option']]);

    // Redirect the user to a different page
    header('Location: results.php');
    exit;
  } else {
    // reCaptcha was unsuccessful
    // Display an error message
    echo "<p style='color: orange;'>There was an error with the reCaptcha. Please try again.</p>";
  }
}

// Display the poll form
echo "<form method='post'>";
foreach ($poll_options as $id => $option) {
  echo "<input type='radio' name='option' value='$id'> $option<br>";
}
echo "<br>";
echo "<div class='g-recaptcha' data-sitekey='6LdClsMjAAAAAF38bS2qAPTERQQRMnEQVFYeCRou'></div>";
echo "<input type='hidden' name='expiry_date' value='02/01/2023'>";
echo "<br>";
echo "<input type='submit' name='submit' value='Vote'>";
echo "</form>";

// Display the "View Results" link
echo "<a href='results.php'>View Results</a>";

?>
