var changeFilterForElements = function(elem)
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
        window.location = gBaseUrl + '/cms';
        return;
    }

    var qsStr = qsArr.join('&');
    window.location = gBaseUrl + '/cms/list?' + qsStr;
}

var triggerSearch = function(e) {
    var evt = e || window.event;
    if (evt.keyCode == 13) {
        changeFilterForElements(null);
        return;
    }
}

var gotoPageNumber = function(number)
{
    $('#cms_page_number').val(number);
    changeFilterForElements(null);
}

var uniqueId = function(idlength)
{
    var charstoformid = '_0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz'.split('');
    if (! idlength) {
        idlength = Math.floor(Math.random() * charstoformid.length);
    }
    var uniqid = '';
    for (var i = 0; i < idlength; i++) {
        uniqid += charstoformid[Math.floor(Math.random() * charstoformid.length)];
    }

    return uniqid;
}

var insertPrimaryLessonObject = function(elem)
{
    $('#Lesson-primary_lesson_type-contents-div').empty();
    var objectType = $(elem).find("option:selected").text().toLowerCase();
    var clone = $('#Lesson-' + objectType + '_div').clone();
    alert(clone);
    $(clone).find('[id]').each(function () {
        var idElem = $(this);
        $(idElem).attr('id', $(idElem).attr('id') + '-' + uniqueId(10));
    });
    $('#Lesson-primary_lesson_type-contents-div').append($(clone));
}

var addGenericEntity = function(elem)
{
    var entityName = $(elem).attr("class").match(/(cm-generic-entity-add-)(\w+)/)[2];
    var lcEntity = entityName.toLowerCase();
    var index = $(elem).parent().children().first().children().length; //.children().length;

    var clone = $('#extra-definitions').find('.cm-generic-entity-' + entityName).clone();
    $(clone).find('[id]').each(function() {
        var idElem = $(this);
        $(idElem).attr('id', $(idElem).attr('id') + '-generic-element-' + uniqueId(10));
    });

    var newAttr = $(elem).parent().children().first().children().last().find("[name*='][" + lcEntity + "]']").first().attr('name');
    if (newAttr) {
        var nearestIndex = newAttr.match(/.*\[(\d+)\].*?\[\d+\].*?/) ? parseInt(newAttr.match(/.*\[(\d+)\].*?\[\d+\].*?/)[1]) : null;
        var nearestAttribute = newAttr.match(/.*?\[(\w+)\]\[\d+\].*?\[\d+\].*?/) ? newAttr.match(/.*?\[(\w+)\]\[\d+\].*?\[\d+\].*?/)[1] : null;
        if (nearestAttribute) {
            $(clone).find("[name*='[" + nearestAttribute + "][0]']").each(function () {
                var nameElem = $(this);
                $(nameElem).attr('name', $(nameElem).attr('name').replace('[' + nearestAttribute + '][0]', '[' + nearestAttribute + '][' + nearestIndex + ']'));
            });
        }
    }

    $(clone).find("[name*='[" + lcEntity + "][0]']").each(function() {
        var nameElem = $(this);
        $(nameElem).attr('name', $(nameElem).attr('name').replace('[' + lcEntity + '][0]', '[' + lcEntity + '][' + index + ']'));
    });

    $(clone).children('small[class^="cm-entity-label"]').children('span').text(entityName + " " + (index + 1));
    $(clone).children('.cm-generic-entity-Element').children('small[class^="cm-entity-label"]').children('span').text(entityName + " " + (index + 1));
    //$(clone).children('.cm-generic-entity-Element').children('.cm-generic-entity-element-contents').children('small[class^="cm-entity-label"]').children('span').text(entityName + " " + (index + 1));

    $(elem).parent().children().first().append($(clone));
}

var reorderGenericEntities = function(elem)
{
    var entityName = $(elem).attr("class").match(/(cm-generic-entity-)(\w+)/)[2];
    var lcEntity = entityName.toLowerCase();
    var index = 0;
    $(elem).parent().children().each(function() {
        $(this).find('[name*="[' + lcEntity + ']"]').each(function() {
            $(this).attr('name', $(this).attr('name').replace(new RegExp("(.*)\\[" + lcEntity + "\\]\\[\\d+\\](.*)"), '$1' + '[' + lcEntity + '][' + index + ']' + '$2'));
            $(this).prev('small[class^="cm-entity-label"]').each(function() {
                if ($(this).children('span').text().match(new RegExp(entityName))) {
                    $(this).children('span').text(entityName + " " + (index +1));
                }
            });
        });
        $(this).children('small[class^="cm-entity-label"]').children('span').text(entityName + " " + (index + 1));
        //$(this).children('.cm-generic-entity-Element').children('small[class^="cm-entity-label"]').children('span').text(entityName + " " + (index + 1));

        index++;
    })
}

var moveGenericEntityUp = function(elem)
{
    var movableEntity = $(elem).closest('div[class^="cm-generic-entity-"]');
    $(movableEntity).insertBefore($(movableEntity).prev());
    reorderGenericEntities(movableEntity);
}

var moveGenericEntityDown = function(elem)
{
    var movableEntity = $(elem).closest('div[class^="cm-generic-entity-"]');
    $(movableEntity).insertAfter($(movableEntity).next());
    reorderGenericEntities(movableEntity);
}

var removeGenericEntity = function(elem)
{
    var movableEntity = $(elem).closest('div[class^="cm-generic-entity-"]');
    $(movableEntity).parent().append($(movableEntity));
    reorderGenericEntities(movableEntity);
    $(movableEntity).remove();
}

var addElement = function(elem)
{
    var nextDiv = $(elem).next();
    var entityName = $(elem).attr("name");
    var elementName = $(elem).parent().children('small[class^="cm-entity-label"]').children('span').first().text();
    $(nextDiv).empty();
    var objectType = $(elem).find("option:selected").text().toLowerCase();
    var baseObjectType = $('#object-type').text();
    $('#' + baseObjectType + '-' + objectType + '_div').find('.cm-generic-element-attribute').each(function() {
        var clone = $(this).clone();
        $(clone).find('[id]').each(function() {
            var idElem = $(this);
            $(idElem).attr('id', $(idElem).attr('id') + '-generic-element-' + uniqueId(10));
        });
        $(clone).find('[name]').each(function() {
            var nameElem = $(this).attr('name').replace(baseObjectType + '-', '');
            var parts = nameElem.split("-")
            var newName = entityName.replace('[type]', '[' + parts[0] + '][' + parts[1] + ']');
            $(this).attr('name', newName);
        });
        var children = $(clone).children();
        $(clone).children('small[class^="cm-entity-label"]').each(function() {
            $(this).children('span').first().text(elementName);
        });
        $(clone).find('small[class^="cm-entity-label"]').each(function() {
            $(this).children('span').first().text(elementName);
        });
        $(nextDiv).append($(clone));
    });
}

var getTagsInTagGroup = function(elem)
{
    $.ajax({
        type: "POST",
        url: gBaseUrl + '/tags/list',
        data: 'tag_group_id=' + $(elem).val(),
        success: function(data) {
            if (data.status = 1) {
                var tagElem = $(elem).next();
                $(tagElem).empty();
                var option = $('<option value="">Select Tag</option>');
                $(tagElem).append(option);
                for(var i = 0; i < data.objects.length; i++) {
                    option = $('<option value="' + data.objects[i].id + '">' + data.objects[i].tag + '</option>');
                    $(tagElem).append(option);
                }
            }
        },
        dataType: 'json'
    });
}

var getLessonsInTag = function(elem)
{
    $.ajax({
        type: "POST",
        url: gBaseUrl + '/tags/get_lessons',
        data: 'tag_id=' + $(elem).val(),
        success: function(data) {
            if (data.status = 1) {
                var tagElem = $(elem).next();
                $(tagElem).empty();
                var option = $('<option value="">Select Lesson</option>');
                $(tagElem).append(option);
                for(var i = 0; i < data.objects.length; i++) {
                    option = $('<option value="' + data.objects[i].id + '">' + data.objects[i].name + '</option>');
                    $(tagElem).append(option);
                }
            }
        },
        dataType: 'json'
    });
}

var getTracksInTag = function(elem)
{
    $.ajax({
        type: "POST",
        url: gBaseUrl + '/tags/get_tracks',
        data: 'tag_id=' + $(elem).val(),
        success: function(data) {
            if (data.status = 1) {
                var tagElem = $(elem).next();
                $(tagElem).empty();
                var option = $('<option value="">Select Track</option>');
                $(tagElem).append(option);
                for(var i = 0; i < data.objects.length; i++) {
                    option = $('<option value="' + data.objects[i].id + '">' + data.objects[i].name + '</option>');
                    $(tagElem).append(option);
                }
            }
        },
        dataType: 'json'
    });
}

var getQuizzesInTag = function(elem)
{
    $.ajax({
        type: "POST",
        url: gBaseUrl + '/tags/get_quizzes',
        data: 'tag_id=' + $(elem).val(),
        success: function(data) {
            if (data.status = 1) {
                var tagElem = $(elem).next();
                $(tagElem).empty();
                var option = $('<option value="">Select Quiz</option>');
                $(tagElem).append(option);
                for(var i = 0; i < data.objects.length; i++) {
                    option = $('<option value="' + data.objects[i].id + '">' + data.objects[i].name + '</option>');
                    $(tagElem).append(option);
                }
            }
        },
        dataType: 'json'
    });
}

var toggleCommitElements = function(elem)
{
    if ($(elem).val() == 1) {
        $(elem).val(0);
        $('input[name="committed_object_ids[]"]').each(function(idx) {
            $(this).prop('checked', false);
        });
    }
    else {
        $(elem).val(1);
        $('input[name="committed_object_ids[]"]').each(function(idx) {
            $(this).prop('checked', true);
        });
    }
}

var publishObjects = function(elem)
{
    if(confirm("Are you sure you want to publish all objects?")) {
        $.ajax({
            type: "POST",
            url: gBaseUrl + '/cms/publish',
            data: '',
            success: function(data) {
                if (data.status = 1) {
                    window.location.assign(gBaseUrl + '/cms/commit');
                }
            },
            dataType: 'json'
        });
    }
}
