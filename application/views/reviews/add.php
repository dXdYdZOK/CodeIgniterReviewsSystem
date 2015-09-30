<?php echo validation_errors(); ?>

<form action='/reviews/<?=$action?>/' method='POST'>

<h2>Review To: <?=anchor('/users/view/'.$to_user->id.'/',$to_user->username.'('.$to_user->first_name.' '.$to_user->last_name.')')?></h2>

<input type='hidden' name='user_id' value='<?=$user_id?>'>
<input type='hidden' name='sender_id' value='<?=$sender_id?>'>

<h5></h5>
<select name='status'>
	<? for($n=-1;$n<=1;$n++): ?>
		<option value='<?=$n?>'<?=$n==set_value('status')?' selected':''?>><?if($n==-1) echo 'Negative'; elseif($n==0) echo 'Neutral'; else echo 'Positive';?></option>
	<? endfor; ?>
</select>

<h5></h5>
<textarea name='text'><?=set_value('text')?></textarea>

<div><input type="submit" value="Сохранить"></div>

</form>
