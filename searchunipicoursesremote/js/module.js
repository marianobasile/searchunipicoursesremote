M.search_courses_remote = M.search_courses_remote || {};

var resultShowLastOne = "<div><a onclick='javascript:document.forms[\"searchCourse\"].submit();'><b>{showAllCourses}</b></a></div>";
var matchesFound = "<div><a href='{courselink}'><b>{highlighted}</b> ({teacher_lastname} {teacher_firstname})</a></div>";

M.search_courses_remote.init = function (Y, params) {

    YUI().use('autocomplete', 'autocomplete-highlighters', 'cookie', function (Y) {

        var InpNode = Y.one('#corso');

        var InpNodeTeacher = Y.one('#docente');


        InpNode.on('focus', function (e) {
            M.search_courses_remote.AutoCompletePlugin(Y, this, params);
        });

        InpNodeTeacher.on('focus', function (e) {
            M.search_courses_remote.AutoCompletePlugin(Y, this, params);
        });

    });
}

M.search_courses_remote.AutoCompletePlugin = function (Y, node, params) {

    function course_Formatter(query, results) {

        return Y.Array.map(results, function (result) {
            var course = result.raw;
            var highlighted = result.highlighted;
            var servAddr = result.raw.serverAddress;
            var courselink = 'http://'+ servAddr +'/elearn/course/view.php?id=' + course.id;
            var teacher_lastname = result.raw.lastname;
            var teacher_firstname = result.raw.firstname;
            var numberOfRecords = result.raw.numberOfRecords;
            var showAllCourses = "VISUALIZZA TUTTI";


            if(!teacher_lastname && !teacher_firstname) {
                teacher_lastname = 'Docente';
                teacher_firstname = 'Mancante';
            }
            
            course_Formatter.countChars = course_Formatter.countChars || node.get('value');

           /* if(node.get('value').length == 0){
                course_Formatter.count = undefined;
                course_Formatter.countChars = undefined;
            } else {*/
                if(course_Formatter.countChars != node.get('value')){
                    course_Formatter.count = undefined;
                    course_Formatter.countChars = node.get('value');
                }
            //}
            
            course_Formatter.count = ++course_Formatter.count || 1;
          
                if ( course_Formatter.count == numberOfRecords ) {
                    course_Formatter.count = undefined;
                    course_Formatter.countChars = undefined;
                    return Y.Lang.sub(resultShowLastOne, {
                    showAllCourses: showAllCourses           
                    });
                } else {
                    return Y.Lang.sub(matchesFound, {
                    servAddr: servAddr,
                    courselink: courselink,
                    highlighted: highlighted,
                    teacher_lastname: teacher_lastname,
                    teacher_firstname: teacher_firstname
                    });
                } 
        });

    }   
    var address;

    if( node == Y.one('#corso') )
        address = M.cfg.wwwroot + '/blocks/searchunipicoursesremote/result_by_course_name.php?query={query}';
    else
        address = M.cfg.wwwroot + '/blocks/searchunipicoursesremote/result_by_teacher.php?query={query}';
    
        node.plug(Y.Plugin.AutoComplete, {
        resultFormatter: course_Formatter,
        resultHighlighter: 'phraseMatch',
        resultListLocator: 'results',
        alwaysShowList: false,
        resultTextLocator: 'fullname',
        source: address
        })
}