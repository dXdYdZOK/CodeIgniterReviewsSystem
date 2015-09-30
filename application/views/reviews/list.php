<style>
	.review.minus {background-color:red;}
	.review.plus {background-color:green;}
	.review.neutral {background-color:#c0c0c0;}
	.review {margin:10px;}
</style>
<div>
	<? //var_dump($reviews); ?>
	<? if(count($reviews)==0): ?>
		<h2>No Reviews to User <?=$username?></h2>
	<? else: ?>
		<h2>Reviews to User <?=$username?></h2>
		<? foreach($reviews as $review): ?>
			<div class='review <? if($review->status==-1) echo "minus"; elseif($review->status==0) echo "neutral"; else echo "plus"; ?>'>
				<div class='from'><span>Review from User: </span><?=anchor("/users/view/{$review->sender_id}/", $review->username.'('.$review->first_name.' '.$review->last_name.')'); ?></div>
				<div class='type'><span>Review type:</span> <? if($review->status==-1) echo "negative"; elseif($review->status==0) echo "neutral"; else echo "positive"; ?>></div>
				<p class='text'><?=$review->text?></p>
				<? if($this->ion_auth->is_admin()||($this->ion_auth->get_user_id()==$review->sender_id)): ?>
					<div class='actions'>
						<?=anchor("/reviews/delete/{$review->id}/", 'Delete Review'); ?>&nbsp;
						<?=anchor("/reviews/edit/{$review->id}/", 'Edit Review'); ?>
					</div>
				<? endif; ?>
			</div>
		<? endforeach; ?>
	<? endif; ?>
</div>

<div>
	<?=$pagination_html?>
</div>
<div class='actions'>
<?=anchor("/reviews/add/{$user_id}/", 'Add Review'); ?>
</div>