<div class="row">
  <div class="span9">
    <div class="alert alert-error hide" id="msg-box"></div>
    <ul class="thumbnails">
      <?php 
      foreach ($trades as $key => $trade) { ?>
      <li class="span9">
        <div class="thumbnail">
          <div class="row">
            <a href="" class="span2">
              <img class="book_image" src="<?php echo $trade['image_url'];?>" >
            </a>
            <div class="span6">
              <?php
              $title_anchor = anchor_popup(site_url('item/detail/'.$trade['item_id']) , $trade['item_title'] );
              $borrower_anchor = anchor_popup(site_url('#') , $trade['borrower_name'] );
              ?>
              <!-- title -->
              <h4><?php echo $title_anchor;?></h4>
              <!-- book owner infomation  -->
              <p>My description :  <span><?php echo $trade['item_description'];?></span></p>
              <p><?php echo $trade['create_time'];?> : <?php echo $borrower_anchor;?> wants to borrow this book . </p>
              <!-- change book -->
              
              <?php if($trade['trade_status'] == 1){ //accept or deny?>
                <p>
                <button class="btn btn-success trade_op" trade_op="accept" trade_id="<?php echo $trade['trade_id'];?>" type="button">Accept</button>
                <button class="btn btn-danger trade_op" trade_op="deny" trade_id="<?php echo $trade['trade_id'];?>" type="button">Deny</button>
                </p>
              <?php }else if($trade['trade_status'] == 2){?>
                <p>You have accepted <?php echo $borrower_anchor;?>'s request.</p>
                <p>
                <button class="btn btn-primary trade_op" trade_op="return" trade_id="<?php echo $trade['trade_id'];?>" type="button">Book is returned</button>
                <button class="btn btn-danger trade_op" trade_op="lost" trade_id="<?php echo $trade['trade_id'];?>" type="button">Book is lost</button>
                </p>
              <?php }else if($trade['trade_status'] == 3){?>
                <p>You have denied <?php echo $borrower_anchor;?>'s request.</p>
              <?php }?>

            </div>
            <div class="span1">

            </div>
          </div>
        </div>
      </li>
      <?php }?>
    </ul>
    <div class="pagination">
      <ul>
      <?php foreach ($link_array as $key => $value) {echo $value;}?>
      </ul>
    </div>
    
  </div>
</div>

<script type="text/javascript">
  var post_url = "<?php echo site_url('api/updateTrade');?>";
  updateTrade(post_url);
</script>

 <style type="text/css">
      .book_image {
        margin-left: 5px;
        margin-top: 5px;
        margin-bottom: 5px;

        width: 100px; 
        height: 130px;
      }
      .share_status{
        margin-left: 25px;
      }
</style>