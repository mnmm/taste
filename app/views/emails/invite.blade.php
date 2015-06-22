<!DOCTYPE html><html lang='en-US'><head><meta charset='utf-8'></head><body><table style='width:100%'><tr style=width:100%'><td bgcolor='#c15702' style='width:100%'><div style='padding:15px;max-width:600px;margin:0 auto;display:block;'><table><tr><td><a href='{{ Request::root() }}' target='_blank'><img src='{{ Request::root() }}/assets/admin/layout/img/taste-black.png' alt='Taste.com' border='0' /></a></td></tr></table></div></td><td></td></tr></table><table style='width: 100%;'> <tbody><tr><td> </td><td bgcolor='#FFFFFF'><div style='padding: 15px; max-width: 600px; margin: 0 auto; display: block;'><table><tbody><tr><td>
    <h2>Welcome to Taste</h2>
    <br >
    <?php if($custommessage!='')
	{?>
	<div>{{ $custommessage }}</div>	
	<?php }?>

</td></tr>	
</tbody></table></div></td><td> </td></tr></tbody></table></body></html>
