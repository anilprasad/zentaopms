function showLink(buildID, type, param)
{
    var method = type == 'story' ? 'linkStory' : 'linkBug';
    $.get(createLink('build', method, 'buildID=' + buildID + (typeof(param) == 'undefined' ? '' : param)), function(data)
    {
        var obj = type == 'story' ? '.tab-pane#stories .linkBox' : '.tab-pane#bugs .linkBox';
        $(obj).html(data);
        $('#' + type + 'List').closest('form').hide();

        var formID = type == 'story' ? '#unlinkedStoriesForm' : '#unlinkedBugsForm';
        setTimeout(function(){fixedTfootAction(formID)}, 100);

        $('[data-ride="table"]').table();
    });
}
$(function()
{
    if(flow != 'onlyTest')
    {
        if(link == 'true') showLink(buildID, type, param);
        fixedTfootAction($('#' + type + 'List').closest('form'));
        $('.nav.nav-tabs a[data-toggle="tab"]').on('shown.zui.tab', function(e)
        {
            var href = $(e.target).attr('href');
            var tabPane = $(href + '.tab-pane');
            if(tabPane.size() == 0) return;
            var formID = tabPane.find('.linkBox').find('form:last');
            if(formID.size() == 0) formID = tabPane.find('form:last');
            setTimeout(function(){fixedTfootAction(formID)}, 100);
        });
    }
})
