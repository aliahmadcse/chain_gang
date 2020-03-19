<?php require_once('../private/initialize.php'); ?>

<?php $page_title = 'Inventory'; ?>
<?php include(SHARED_PATH . '/public_header.php'); ?>


<div id="main">

  <div id="page">
    <div class="intro">
      <img class="inset" src="<?php echo url_for('/images/AdobeStock_55807979_thumb.jpeg') ?>" />
      <h2>Our Inventory of Used Bicycles</h2>
      <p>Choose the bike you love.</p>
      <p>We will deliver it to your door and let you try it before you buy it.</p>
    </div>

    <table id="inventory">
      <tr>
        <th>Brand</th>
        <th>Model</th>
        <th>Year</th>
        <th>Category</th>
        <th>Gender</th>
        <th>Color</th>
        <th>Weight</th>
        <th>Condition</th>
        <th>Price</th>
      </tr>
      <?php
      $parser = new ParseCSV(PRIVATE_PATH . '/used_bicycles.csv');
      $bike_array = $parser->parse();

      // print_r($bike_array);
      foreach ($bike_array as $args) {
        // $args = ['brand' => 'Trek', 'model' => 'Emonda', 'year' => '2017'];
        $bicylce = new Bicycle($args);

      ?>
        <tr>
          <td><?php echo h($bicylce->brand); ?></td>
          <td><?php echo h($bicylce->model); ?></td>
          <td><?php echo h($bicylce->year); ?></td>
          <td><?php echo h($bicylce->category); ?></td>
          <td><?php echo h($bicylce->gender); ?></td>
          <td><?php echo h($bicylce->color); ?></td>
          <td><?php echo h($bicylce->weight_kg()) . ' / ' . h($bicylce->weight_lbs()); ?></td>
          <td><?php echo h($bicylce->condition()); ?></td>
          <td><?php echo "$" . h(number_format($bicylce->price(), 2)); ?></td>
        </tr>
      <?php } ?>
    </table>
  </div>

</div>

<?php include(SHARED_PATH . '/public_footer.php'); ?>