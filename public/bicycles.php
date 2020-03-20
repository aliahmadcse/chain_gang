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
        <th>Price</th>
        <th>&nbsp;</th>
      </tr>
      <?php
      $bicylce_objects_array = Bicycle::find_all();
      foreach ($bicylce_objects_array as $bicylce) {
      ?>
        <tr>
          <td><?php echo h($bicylce->brand); ?></td>
          <td><?php echo h($bicylce->model); ?></td>
          <td><?php echo h($bicylce->year); ?></td>
          <td><?php echo h($bicylce->category); ?></td>
          <td><?php echo h($bicylce->gender); ?></td>
          <td><?php echo h($bicylce->color); ?></td>
          <td><?php echo "$" . h(number_format($bicylce->price(), 2)); ?></td>
          <td><a href="detail.php?id=<?php echo h($bicylce->id); ?>">View</a></td>
        </tr>
      <?php } ?>
    </table>

  </div>

</div>

<?php include(SHARED_PATH . '/public_footer.php'); ?>