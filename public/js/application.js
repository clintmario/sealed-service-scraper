var changeFilterForElements = function(elem, path)
{
    var filters = {};
    var qsArr = [];
    $('#cms-tag-elements').find("select").each(function (idx) {
        if ($(this).val() != 0) {
            filters[$(this).attr('id')] = $(this).val();
        }
    });

    if ($('#search_keywords').val()) {
        filters['search_keywords'] = $('#search_keywords').val();
    }

    if ($('#cms_object_type').val()) {
        filters['object_type'] = $('#cms_object_type').val();
    }

    if ($('#cms_page_number').val()) {
        filters['page_num'] = $('#cms_page_number').val();
    }

    for (var i in filters) {
        var str = i + '=' + encodeURIComponent(filters[i]);
        qsArr.push(str);
    }

    if (qsArr.length == 0) {
        window.location = gBaseUrl + '/' + path;
        return;
    }

    var qsStr = qsArr.join('&');
    window.location = gBaseUrl + '/' + path + '?' + qsStr;
}

var triggerSearch = function(e) {
    var evt = e || window.event;
    if (evt.keyCode == 13) {
        changeFilterForElements(null);
        return;
    }
}

var gotoPageNumber = function(number, path)
{
    $('#cms_page_number').val(number);
    changeFilterForElements(null, path);
}