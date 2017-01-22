<?php
/**
 * GeniXCMS - Content Management System.
 *
 * PHP Based Content Management System and Framework
 *
 * @since 0.0.8 build date 20160315
 *
 * @version 1.0.0
 *
 * @link https://github.com/semplon/GeniXCMS
 * @link http://genixcms.org
 *
 * @author Puguh Wijayanto <psw@metalgenix.com>
 * @copyright 2014-2017 Puguh Wijayanto
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 */
?>
<div class="row">
    <div class="col-md-12">
        <?=Hooks::run('admin_page_notif_action', $data);?>
    </div>
    <div class="col-md-12">
        <h2><i class="fa fa-tags"></i>  <?=TAGS;?>
            <button class="btn btn-success pull-right" data-toggle="modal" data-target="#myModal">
                <span class="glyphicon glyphicon-plus"></span> 
                <span class="hidden-xs hidden-sm"><?=ADD_TAG;?></span>
            </button>
        </h2>
        <small class="label label-default pull-left"><?=Stats::totalCat('tag');?> total</small>

        <hr />
    </div>

    <div class="col-sm-12">
        <div class="row">
            <?php
            if ($data['num'] > 0) {
                foreach ($data['cat'] as $c) {
                    // echo "<td>".$c->id."</td>";
                    // echo "<td>".$c->name."</td>";
                    // echo "<td>".$c->parent."</td>";
                    // echo "<td></td>";

                    if ($c->parent == '' || $c->parent == 0) {
                        echo "<div class=\"col-sm-6 col-md-4 item\" >
                            <div class=\"panel panel-default\">
                                <div class=\"panel-heading\">
                                    <form action=\"index.php?page=tags\" method=\"POST\" name=\"updatecat\">
                                    <div class=\"input-group\">
                                        <a href=\"?page=tags&act=del&id={$c->id}&token=".TOKEN."\" class=\"input-group-addon\"
                                        onclick=\"return confirm('Are you sure you want to delete this item?');\"
                                        ><span class=\"glyphicon glyphicon-remove\"></span></a>
                                        <input type=\"text\" name=\"cat\" class=\"form-control\" value=\"{$c->name}\">
                                        <input type=\"hidden\" name=\"id\" value=\"{$c->id}\">
                                        <input type=\"hidden\" name=\"token\" value=\"".TOKEN.'">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="submit" name="updatecat">Go!</button>
                                        </span>
                                    </div>
                                    </form>

                                </div>
                            </div>';
                        echo '</div>';
                    }
                }
            } else {
                echo '<div class="col-md-12">No Tags Found</div>';
            }
            ?>
        </div>



    </div>
</div>
<!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
            <form action="index.php?page=tags" method="post">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel"><?=ADD_TAG;?></h4>
          </div>
          <div class="modal-body">
                <div class="form-group">
                    <label><?=TAG_NAME;?></label>
                    <input type="text" name="cat" class="form-control">
                </div>

          </div>
          <div class="modal-footer">
            <input type="hidden" name="token" value="<?=TOKEN;?>">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?=CLOSE;?></button>
            <button type="submit" class="btn btn-success" name="addcat"><?=SUBMIT;?></button>
          </div>
          </form>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
