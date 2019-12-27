<!-- https://gist.github.com/elidickinson/9424116#file-html_email_buttons_1-html -->
<div style="display:inline-block;width:190px">
<!--[if mso]>
  <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="<?php echo e($link); ?>" style="height:44px;v-text-anchor:middle;width:180px;" arcsize="10%" stroke="f" fillcolor="<?php echo e($color); ?>">
    <w:anchorlock/>
    <center style="color:#ffffff;font-family:sans-serif;font-size:16px;font-weight:bold;">
      <?php echo e(trans("texts.{$field}")); ?>

    </center>
  </v:roundrect>
  <![endif]-->
  <![if !mso]>
  <table cellspacing="0" cellpadding="0"> <tr> 
  <td align="center" width="180" height="44" bgcolor="<?php echo e($color); ?>" style="-webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; color: #ffffff; display: block;">
    <a href="<?php echo e($link); ?>" style="font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:44px; width:100%; display:inline-block">
    <span style="color: #ffffff;">
      <?php echo e(trans("texts.{$field}")); ?>

    </span>
    </a>
  </td> 
  </tr> </table> 
  <![endif]>
</div>