<?php
/**
 * The create view of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id: create.html.php 4903 2013-06-26 05:32:59Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
include '../../common/view/header.html.php';
include '../../common/view/form.html.php';
include '../../common/view/kindeditor.html.php';
js::set('holders', $lang->bug->placeholder);
js::set('page', 'create');
js::set('createRelease', $lang->release->create);
js::set('createBuild', $lang->build->create);
js::set('refresh', $lang->refresh);
?>
<div class='container mw-1400px'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'><?php echo html::icon($lang->icons['bug']);?></span>
      <strong><small class='text-muted'><?php echo html::icon($lang->icons['create']);?></small> <?php echo $lang->bug->create;?></strong>
    </div>
  </div>
  <form class='form-condensed' method='post' enctype='multipart/form-data' id='dataform' data-type='ajax'>
    <table class='table table-form'> 
      <tr>
        <th class='w-110px'><?php echo $lang->bug->product;?></th>
        <td class='w-p45-f'>
          <div class='input-group'>
            <?php echo html::select('product', $products, $productID, "onchange='loadAll(this.value);' class='form-control chosen' autocomplete='off'");?>
            <?php if($this->session->currentProductType != 'normal') echo html::select('branch', $branches, $branch, "onchange='loadBranch()' class='form-control' style='width:120px'");?>
          </div>
        </td>
        <td>
          <div class='input-group' id='moduleIdBox'>
            <span class="input-group-addon"><?php echo $lang->bug->module?></span>
            <?php
            echo html::select('module', $moduleOptionMenu, $moduleID, "onchange='loadModuleRelated()' class='form-control chosen'");
            if(count($moduleOptionMenu) == 1)
            {
                echo "<span class='input-group-addon'>";
                echo html::a($this->createLink('tree', 'browse', "rootID=$productID&view=bug&currentModuleID=0&branch=$branch"), $lang->tree->manage, '_blank');
                echo '&nbsp; ';
                echo html::a("javascript:loadProductModules($productID)", $lang->refresh);
                echo '</span>';
            }
            ?>
          </div>
        </td>
        <td></td>
      </tr>
      <tr>
        <th><?php echo $lang->bug->project;?></th>
        <td><span id='projectIdBox'><?php echo html::select('project', $projects, $projectID, "class='form-control chosen' onchange='loadProjectRelated(this.value)' autocomplete='off'");?></span></td>
        <td>
          <div class='input-group'>
            <span class='input-group-addon'><?php echo $lang->bug->openedBuild?></span>
            <span id='buildBox'><?php echo html::select('openedBuild[]', $builds, $buildID, "size=4 multiple=multiple class='chosen form-control'");?></span>
            <span class='input-group-addon' id='buildBoxActions'></span>
            <span class='input-group-btn'><?php echo html::commonButton('<i class="icon icon-refresh"></i>', "class='btn btn-default' data-toggle='tooltip' onclick='loadAllBuilds()' title='{$lang->bug->allBuilds}' style='border-left: none'")?></span>
          </div>
        </td>
      </tr>
      <tr>
        <th><nobr><?php echo $lang->bug->lblAssignedTo;?></nobr></th>
        <td>
          <div class='input-group'>
            <span id='assignedToBox'><?php echo html::select('assignedTo', $projectMembers, $assignedTo, "class='form-control chosen'");?></span>
            <span class='input-group-btn'><?php echo html::commonButton('<i class="icon icon-refresh"></i>', "class='btn btn-default' onclick='loadAllUsers()' data-toggle='tooltip' title='{$lang->bug->allUsers}'");?></span>
          </div>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->bug->type;?></th>
        <td>
          <div class='input-group'>
            <?php
            /* Remove the unused types. */
            unset($lang->bug->typeList['designchange']);
            unset($lang->bug->typeList['newfeature']);
            unset($lang->bug->typeList['trackthings']);
            echo html::select('type', $lang->bug->typeList, $type, "class='form-control'");
            ?>
            <span class='input-group-addon fix-border'><?php echo $lang->bug->os?></span>
            <?php echo html::select('os', $lang->bug->osList, $os, "class='form-control'");?>
            <span class='input-group-addon fix-border'><?php echo $lang->bug->browser?></span>
            <?php echo html::select('browser', $lang->bug->browserList, $browser, "class='form-control'");?>
          </div>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->bug->title;?></th>
        <td colspan='2'>
          <div class='row-table'>
            <div class='col-table w-p100'><?php echo html::input('title', $bugTitle, "class='form-control'");?></div>
            <div class='col-table'>
              <div class='input-group'>
                <span class='input-group-addon fix-border br-0'><?php echo $lang->bug->severity;?></span>
                <?php
                $isAllNumberPri = true;
                foreach ($lang->bug->severityList as $value)
                {
                    if(!empty($value) && !is_numeric($value))
                    {
                        $isAllNumberPri = false;
                        break;
                    }
                }
                ?>
                <?php if(!$isAllNumberPri):?>
                <?php echo html::select('pri', (array)$lang->bug->severityList, $severity, "class='form-control minw-80px'");?> 
                <?php else: ?>
                <div class='input-group-btn dropdown-pris' data-prefix='severity'>
                  <button type='button' class='btn dropdown-toggle br-0' data-toggle='dropdown'>
                    <span class='pri-text'></span> &nbsp;<span class='caret'></span>
                  </button>
                  <ul class='dropdown-menu pull-right'></ul>
                  <?php echo html::select('severity', (array)$lang->bug->severityList, $severity, "class='hide'");?>
                </div>
                <?php endif; ?>
                <span class='input-group-addon fix-border br-0'><?php echo $lang->bug->pri;?></span>
                <?php
                $isAllNumberPri = true;
                foreach ($lang->bug->priList as $value)
                {
                    if(!empty($value) && !is_numeric($value))
                    {
                        $isAllNumberPri = false;
                        break;
                    }
                }
                ?>
                <?php if(!$isAllNumberPri):?>
                <?php echo html::select('pri', (array)$lang->bug->priList, $pri, "class='form-control minw-80px'");?> 
                <?php else: ?>
                <div class='input-group-btn dropdown-pris'>
                  <button type='button' class='btn dropdown-toggle br-0' data-toggle='dropdown'>
                    <span class='pri-text'></span> &nbsp;<span class='caret'></span>
                  </button>
                  <ul class='dropdown-menu pull-right'></ul>
                  <?php echo html::select('pri', $lang->bug->priList, '', "class='hide'");?>
                </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </td>
      </tr>  
      <tr>
        <th><?php echo $lang->bug->steps;?></th>
        <td colspan='2'>
          <div id='tplBoxWrapper'>
            <div class='btn-toolbar'>
              <div class='btn-group'>
                <button id='saveTplBtn' type='button' class='btn btn-mini'><?php echo $lang->bug->saveTemplate?></button>
                <button type='button' class='btn btn-mini dropdown-toggle' data-toggle='dropdown'><?php echo $lang->bug->applyTemplate?> <span class='caret'></span></button>
                <ul id='tplBox' class='dropdown-menu pull-right'>
                  <?php echo $this->fetch('bug', 'buildTemplates');?>
                </ul>
              </div>
            </div>
          </div>
          <?php echo html::textarea('steps', $steps, "rows='5' class='form-control'");?>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->bug->story;?></th>
        <td>
          <span id='storyIdBox'><?php echo html::select('story', empty($stories) ? '' : $stories, $storyID, "class='form-control chosen'");?></span>
        </td>
        <td>
          <div class='input-group'>
            <span class='input-group-addon'><?php echo $lang->bug->task?></span>
            <span id='taskIdBox'> <?php echo html::select('task', '', $taskID, "class='form-control chosen'");?></span>
          </div>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->bug->lblMailto;?></th>
        <td>
          <div class='input-group' id='contactListGroup'>
          <?php 
          echo html::select('mailto[]', $users, str_replace(' ', '', $mailto), "class='form-control chosen' multiple");
          if($contactLists) echo html::select('', $contactLists, '', "class='form-control' style='min-width: 100px; margin-left: -1px' onchange=\"setMailto('mailto', this.value)\"");
          if(empty($contactLists))
          {
              echo '<span class="input-group-btn">';
              echo '<a href="' . $this->createLink('company', 'browse') . '" target="_blank" data-toggle="tooltip" class="btn" title="' . $lang->user->contacts->manage . '"><i class="icon icon-cog"></i></a>';
              echo '<a href="###" onclick="ajaxGetContacts(this)" data-toggle="tooltip" class="btn" title="' . $lang->refresh . '"><i class="icon icon-refresh"></i></a>';
              echo '</span>';
          }
          ?>
          </div>
        </td>
        <td>
          <div class='input-group'>
            <span class='input-group-addon' id='keywordsAddonLabel'><?php echo $lang->bug->keywords;?></span>
            <?php echo html::input('keywords', $keywords, "class='form-control'");?>
          </div>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->bug->files;?></th>
        <td colspan='2'><?php echo $this->fetch('file', 'buildform', 'fileCount=1&percent=0.85');?></td>
      </tr>
      <tr>
        <td></td>
        <td colspan='2'>
          <?php
          echo html::submitButton() . html::backButton();
          echo html::hidden('case', (int)$caseID) . html::hidden('caseVersion', (int)$version);
          echo html::hidden('result', (int)$runID) . html::hidden('testtask', (int)$testtask);
          ?>
        </td>
      </tr>
    </table>
  </form>
</div>
<?php js::set('bugModule', $lang->bug->module);?>
<?php include '../../common/view/footer.html.php';?>
