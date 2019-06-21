<?php if (!defined('THINK_PATH')) exit(); if(is_array($dataList)): foreach($dataList as $key=>$vo): if(($vo["menu_level"]) == "0"): ?><div class="list-group">
	<h1 title="<?php echo ($vo["menu_title"]); ?>"><img src="<?php echo ($vo["menu_icon"]); ?>" /></h1>
	<div class="list-wrap"><h2><?php echo ($vo["menu_full_title"]); ?><i></i></h2>
      <ul>


<?php if(is_array($dataList)): foreach($dataList as $key=>$vvo): if(($vvo["menu_pid"]) == $vo["id"]): ?><li>
       
       <a navid="nav<?php echo ($vvo["id"]); ?>" target="mainframe"> <span><?php echo ($vvo["menu_title"]); ?></span></a>
        <ul>

        
        <?php if(is_array($dataList)): foreach($dataList as $key=>$vvvo): if(($vvvo["menu_pid"]) == $vvo["id"]): ?><li> <a navid="nav<?php echo ($vvvo["id"]); ?>" target="mainframe" href="<?php echo ($vvvo["menu_url"]); ?>"> <span><?php echo ($vvvo["menu_title"]); ?></span> </a></li><?php endif; endforeach; endif; ?>
        </ul>
    	</li><?php endif; endforeach; endif; ?>

		</ul>
	</div>
	</div><?php endif; endforeach; endif; ?>