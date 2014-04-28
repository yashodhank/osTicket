<?php
if (!$info['title'])
    $info['title'] = Format::htmlchars($user->getName());
?>
<h3><?php echo $info['title']; ?></h3>
<b><a class="close" href="#"><i class="icon-remove-circle"></i></a></b>
<hr/>
<?php
if ($info['error']) {
    echo sprintf('<p id="msg_error">%s</p>', $info['error']);
} elseif ($info['msg']) {
    echo sprintf('<p id="msg_notice">%s</p>', $info['msg']);
} ?>
<div id="user-profile" style="display:<?php echo $forms ? 'none' : 'block'; ?>;margin:5px;">
    <i class="icon-user icon-4x pull-left icon-border"></i>
    <?php
    if ($ticket) { ?>
    <a class="action-button pull-right change-user" style="overflow:inherit"
        href="#tickets/<?php echo $ticket->getId(); ?>/change-user" ><i class="icon-user"></i> Change User</a>
    <?php
    } ?>
    <div><b><?php
    echo Format::htmlchars($user->getName()->getOriginal()); ?></b></div>
    <div class="faded">&lt;<?php echo $user->getEmail(); ?>&gt;</div>
    <?php
    if (($org=$user->getOrganization())) { ?>
    <div style="margin-top: 7px;"><?php echo $org->getName(); ?></div>
    <?php
    } ?>

<div class="clear"></div>
<ul class="tabs" style="margin-top:5px">
    <li><a href="#info-tab" class="active"
        ><i class="icon-info-sign"></i>&nbsp;User</a></li>
<?php if ($org) { ?>
    <li><a href="#organization-tab"
        ><i class="icon-fixed-width icon-building"></i>&nbsp;Organization</a></li>
<?php }
    $ext_id = "U".$user->getId();
    if (($notes = QuickNote::forUser($user, $org)->all())) { ?>
    <li><a href="#notes-tab"
        ><i class="icon-fixed-width icon-pushpin"></i>&nbsp;Notes</a></li>
<?php } ?>
</ul>

<div class="tab_content" id="info-tab">
<div class="floating-options">
    <a href="#" id="edituser" class="action" title="Edit"><i class="icon-edit"></i></a>
    <a href="users.php?id=<?php echo $user->getId(); ?>" title="Manage User"
        class="action"><i class="icon-share"></i></a>
</div>
    <table class="custom-info">
<?php foreach ($user->getDynamicData() as $entry) {
?>
    <tr><th colspan="2"><strong><?php
         echo $entry->getForm()->get('title'); ?></strong></td></tr>
<?php foreach ($entry->getAnswers() as $a) { ?>
    <tr><td style="width:30%;"><?php echo Format::htmlchars($a->getField()->get('label'));
         ?>:</td>
    <td><?php echo $a->display(); ?></td>
    </tr>
<?php }
}
?>
    </table>
</div>

<?php if ($org) { ?>
<div class="tab_content" id="organization-tab" style="display:none">
<div class="floating-options">
    <a href="orgs.php?id=<?php echo $org->getId(); ?>" title="Manage Organization"
        class="action"><i class="icon-share"></i></a>
</div>
    <table class="custom-info" width="100%">
<?php foreach ($org->getDynamicData() as $entry) {
?>
    <tr><th colspan="2"><strong><?php
         echo $entry->getForm()->get('title'); ?></strong></td></tr>
<?php foreach ($entry->getAnswers() as $a) { ?>
    <tr><td style="width:30%"><?php echo Format::htmlchars($a->getField()->get('label'));
         ?>:</td>
    <td><?php echo $a->display(); ?></td>
    </tr>
<?php }
}
?>
    </table>
</div>
<?php } # endif ($org) ?>

<div class="tab_content" id="notes-tab" style="display:none">
<?php $show_options = true;
foreach ($notes as $note)
    include STAFFINC_DIR . 'templates/note.tmpl.php';
?>
<div class="quicknote no-options" id="new-note"
    data-url="users/<?php echo $user->getId(); ?>/note">
<div class="body">
    <a href="#"><i class="icon-plus icon-large"></i> &nbsp; Click to create a new note</a>
</div>
</div>
</div>

</div>
<div id="user-form" style="display:<?php echo $forms ? 'block' : 'none'; ?>;">
<div><p id="msg_info"><i class="icon-info-sign"></i>&nbsp; Please note that updates will be reflected system-wide.</p></div>
<?php
$action = $info['action'] ? $info['action'] : ('#users/'.$user->getId());
if ($ticket && $ticket->getOwnerId() == $user->getId())
    $action = '#tickets/'.$ticket->getId().'/user';
?>
<form method="post" class="user" action="<?php echo $action; ?>">
    <input type="hidden" name="uid" value="<?php echo $user->getId(); ?>" />
    <table width="100%">
    <?php
        if (!$forms) $forms = $user->getForms();
        foreach ($forms as $form)
            $form->render();
    ?>
    </table>
    <hr>
    <p class="full-width">
        <span class="buttons" style="float:left">
            <input type="reset" value="Reset">
            <input type="button" name="cancel" class="<?php
    echo ($ticket && $user) ? 'cancel' : 'close' ?>"  value="Cancel">
        </span>
        <span class="buttons" style="float:right">
            <input type="submit" value="Update User">
        </span>
     </p>
</form>
</div>
<div class="clear"></div>
<script type="text/javascript">
$(function() {
    $('a#edituser').click( function(e) {
        e.preventDefault();
        $('div#user-profile').hide();
        $('div#user-form').fadeIn();
        return false;
     });

    $(document).on('click', 'form.user input.cancel', function (e) {
        e.preventDefault();
        $('div#user-form').hide();
        $('div#user-profile').fadeIn();
        return false;
     });
});
</script>
