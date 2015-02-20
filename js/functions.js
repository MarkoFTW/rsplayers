 $(document).ready(function() {
     
    /*var selector = '.nav li';

    $(selector).on('click', function(){
        $(selector).eq(0).removeClass("active");
        $(this).addClass('active');
    });
     
    if (window.location.href.indexOf("?p=rstrack") > -1) {
        $(selector).eq(0).removeClass("active");
        $(selector).eq(2).addClass('active');
    }*/
     
    $('a.btn.btn-success').on('click', function(e) {
        $('.close-reload').attr("disabled", "disabled");
        //var data1 = $("#optVal option:selected").text();
        var data = $("#optVal").val(),
            modal = $(this).data('modal');
        $(modal).find('.modal-body p').html( '<br/> Loading&hellip; <img src="./img/loader.gif"/>' )
        .end().modal('show');
        $.post( "./pages/startTrack.php", {
                clan: data
                //TID: data.eq(0).text(),
             }, function( data ) {
            $(modal).find('.modal-title').html( 'Log' ).end()
            .find('.modal-body').html( data );
            $(modal).modal('show');
            $('.close-reload').removeAttr("disabled", "disabled");
         });
    });
    
    $('#activeTrackers tr > td > a.btn.btn-primary.btn-xs').on('click', function(e) {
        $('.close-reload').attr("disabled", "disabled");
        var data = $(this).closest('tr').find('>td:lt(4)'),
            modal = $(this).data('modal');
        $(modal).find('.modal-body p').html('<br/> Loading&hellip; <img src="./img/loader.gif"/>')
        .end().modal('show');
        $.post( "./pages/stopTrack.php"/*$(this).data('href')*/, {
                clan: data.eq(0).text(),
                TID: data.eq(0).text(),
             }, function( data ) {
            $(modal).find('.modal-title').html( 'Log' ).end()
            .find('.modal-body').html( data );
            $(modal).modal('show');
            $('.close-reload').removeAttr("disabled", "disabled");
         });
    });
    
    
    $('#clanpages tr > td > a.btn.btn-primary.btn-xs').on('click', function(e) {
        var data = $(this).closest('tr').find('>td:lt(4)'),
            modal = $(this).data('modal');
        $(modal).find('.modal-body p').html( 'Sending Data to the server for clan ' + data.eq(1).text() + " with ID " + data.eq(0).text())
        .end().modal('show');
        $.post( "./pages/showResults.php"/*$(this).data('href')*/, {
                CLAN: data.eq(2).text(),
                TID: data.eq(0).text(),
             }, function( data ) {
            $(modal).find('.modal-title').html( 'Results' ).end()
            .find('.modal-body').html( data );
            $(modal).modal('show');
            $("#myTableEr").tablesorter({
                sortList: [[0,0]],
                widgets: [ 'filter' ]
            });
         });
    });
    
     
    $("#myTable").tablesorter(); //zebra
    
    //$('#myTable tr').each(function(){
        //var timer = $('#myTable tr:nth-child(2)').text();
        //var timer = $(this).find('tr').eq(1).text();
        //$("#myTable tr:gt(0)").append("<td>"+ timer +"</td>");
        //$("#myTable tr:gt(0)").append("<td>"+ timer +"</td>");
    //});
    
    $('.myTable1 tr:gt(0)').each(function() {
        var td = $(this).find("td").eq(2).text().replace(/ /g,'T');
        var td1 = td += 'Z';
        //$(this).append("<td>"+ td +"</td>");2014-11-04T22:34:50Z
        //$(this).append("<td>"+ td1 +"</td>");
        $(this).append("<td><time class='examples' datetime='"+td1+"' data-time-tooltip> <span data-time-label=\"#_in\"></span><span data-time-label=\"td_d\" class=\"number\"></span> days, <span data-time-label=\"d_h\" class=\"number\"></span> hours, <span data-time-label=\"d_mm\" class=\"number\"></span> minutes and <span data-time-label=\"d_ss\" class=\"number\"></span> seconds <span data-time-label=\"#ago\"></span></time></td>");
    });
    $('.myTable3222 tr:gt(0)').each(function() {
        var td = $(this).find("td").eq(3).text().replace(/ /g,'T');
        var td1 = td += 'Z';
        //$(this).append("<td>"+ td +"</td>");2014-11-04T22:34:50Z
        //$(this).append("<td>"+ td1 +"</td>");
        $(this).append("<td><time class='examples' datetime='"+td1+"' data-time-tooltip> <span data-time-label=\"#_in\"></span><span data-time-label=\"td_d\" class=\"number\"></span> days, <span data-time-label=\"d_h\" class=\"number\"></span> hours, <span data-time-label=\"d_mm\" class=\"number\"></span> minutes and <span data-time-label=\"d_ss\" class=\"number\"></span> seconds <span data-time-label=\"#ago\"></span></time></td>");
    });
    
    
    $(".close-reload").click(function(){
        window.location.reload();
    });
    
     $("input[type=password]").not("#passwordCurr, #passwordCurrE").keyup(function(){
        var ucase = new RegExp("[A-Z]+");
	var lcase = new RegExp("[a-z]+");
	var num = new RegExp("[0-9]+");
	
	if($("#password1").val().length >= 8){
            $("#8char").removeClass("glyphicon-remove");
            $("#8char").addClass("glyphicon-ok");
            $("#8char").css("color","#00A41E");
	}else{
            $("#8char").removeClass("glyphicon-ok");
            $("#8char").addClass("glyphicon-remove");
            $("#8char").css("color","#FF0004");
	}
	
	if(ucase.test($("#password1").val())){
            $("#ucase").removeClass("glyphicon-remove");
            $("#ucase").addClass("glyphicon-ok");
            $("#ucase").css("color","#00A41E");
	}else{
            $("#ucase").removeClass("glyphicon-ok");
            $("#ucase").addClass("glyphicon-remove");
            $("#ucase").css("color","#FF0004");
	}
	
	if(lcase.test($("#password1").val())){
            $("#lcase").removeClass("glyphicon-remove");
            $("#lcase").addClass("glyphicon-ok");
            $("#lcase").css("color","#00A41E");
	}else{
            $("#lcase").removeClass("glyphicon-ok");
            $("#lcase").addClass("glyphicon-remove");
            $("#lcase").css("color","#FF0004");
	}
	
	if(num.test($("#password1").val())){
            $("#num").removeClass("glyphicon-remove");
            $("#num").addClass("glyphicon-ok");
            $("#num").css("color","#00A41E");
	}else{
            $("#num").removeClass("glyphicon-ok");
            $("#num").addClass("glyphicon-remove");
            $("#num").css("color","#FF0004");
	}
	
	if($("#password1").val() === $("#password2").val()){
            $("#pwmatch").removeClass("glyphicon-remove");
            $("#pwmatch").addClass("glyphicon-ok");
            $("#pwmatch").css("color","#00A41E");
	}else{
            $("#pwmatch").removeClass("glyphicon-ok");
            $("#pwmatch").addClass("glyphicon-remove");
            $("#pwmatch").css("color","#FF0004");
	}
    });
     
     
    $("#aboutRT, #errRegUE, #errRegPW, #succReg, #errLog, #succLog").hide();
    $('a[href="#About"]').click(function(){
        $("#aboutRT").show();
        $("#onlinePlayers").hide();
        $("#wstats").hide();
    });
    
     $("#manageClans").click(function(){
        //var parameter = $(this).val();
        window.location = "/rsplayers/index.php?p=rstrack&clan=manage";
    });
    
    $("#copypastelist").click(function(){ 
       window.location = "/rsplayers/index.php?p=rstrack&upload=cp";
    });
    $("#uploadList").click(function(){
        window.location = "/rsplayers/index.php?p=rstrack&upload=file";
    });
    $("#startANDstop").click(function(){
        window.location = "/rsplayers/index.php?p=rstrack&sns";
    });
    $("#viewStats").click(function(){
        window.location = "/rsplayers/index.php?p=rstrack&stats";
    });
    $("#2").click(function(){
        window.location = "/rsplayers/index.php?p=rstrack&clan=manage";
    });
    
     
     
    $(function() {

        $.livetime.options.serverTimeUrl = '/var/www/rsplayers/empty.txt';

        $.livetime.options.formats.livetest = "td_dd:td_hh:d_mm:d_ss";

        $.livetime.options.formats.customwithduration = [
            [-20, '#fulldate'],
            [-5, 'will start in a few seconds'],
            [0, 'will start in less than 5 seconds'],
            ['end-5', 'playing (end_td_s seconds remaining)'],
            ['end', 'playing (about to finish)'],
            ['end+60', 'finished'],
            ['finished (end_td_m minutes ago)']
        ];

        $.livetime.options.formats.custom = [
            [-120, 'td_m minutes remaining'],
            [-30, 'td_s seconds remaining'],
            [-15, 'in a moment'],
            [-5, 'td_s seconds remaining'],
            [-4, 'Four!'],
            [-3, '<b>THREE!</b>'],
            [-2, '<b>TWO!</b>'],
            [-1, '<b>ONE!</b>'],
            [0, '<big><b>ZERO!</b></big>'],
            [3, 'Preparing'],
            [5, 'Almost ready'],
            [10, 'Working'],
            [14, 'Almost complete'],
            ['Completed']
        ];

        var bars = [];
        for (var i=-30; i<30; i++) {
            bars.push([i, new Array(Math.abs(i)).join(i>0 ? '*':'@')]);
        }
        bars.push(['END']);
        $.livetime.options.formats.custom2 = bars;

        $.livetime.options.formats.custom3 = [
            [0, 'Upcoming'],
            ['Done']
        ];

        // set all timestamp to 20s in the futute
        //$('[datetime]').attr('datetime', new Date().getTime()+20000
          //  -new Date().getTimezoneOffset()*60000);

        $('.examples').on('refreshComplete', function(e, data){
            if (data.htmlChanged && data.nextRefreshMs > 100) {
                var root = $(e.target);
                var labels = root.find('[data-time-label]');
                if (root.is('[data-time-label]')) {
                    labels = labels.add(root);
                }
                labels.addClass('timer-tick');
                    setTimeout(function(){
                        labels.removeClass('timer-tick');
                    },80);
            }
            if ($.livetime.localTimeOffset) {
                $('.localtimeoffsetseconds').text(Math.floor($.livetime.localTimeOffset/1000));
            }

        });

        $('.examples').livetime();

        $('.addtime a').click(function(){
            $('[datetime]').attr('datetime', parseInt($('[datetime]').attr('datetime')||0) +
                parseInt($(this).data('seconds'))*1000);
            $('.examples').livetime();
        });
        $('.timestop').click(function(){
            $('.examples').livetime(false);
        });
        $('.examples .removedatetime').click(function(){
            $(this).parent().remove();
        });

        $('.livetest-input').on('change, keyup', function(){
            $.livetime.options.formats.livetest = $(this).val();
            $('.livetest time').livetime();
        });

    }); 
     
     
     
     
     
     
     
     $('#regnow').click(function(){
        var uname = $.trim($("#inputUsername").val());
        var pwd = $.trim($("#inputPassword1").val());
        var pwdConfirm = $.trim($("#inputPassword2").val());
        var mail = $.trim($("#inputEmail1").val());
        
        if(pwd === pwdConfirm) {
            //alert(uname + pwd + mail);
            $.post( "./pages/reg.php", { Username:uname, Password:pwd, Email:mail }, function(result){
                //alert("Username: " + uname + "Password" + pwd + "Email" + mail);
                //alert("Registration successfull");
                if(result === "truefalsefalse") {
                    //alert(result);
                    $("#succReg").show().delay(2000).fadeOut();
                    $.post( "./pages/loginUser.php", { UserPasswordLogin:pwd, UserMailLogin:mail }, function(){
                        //alert("Password" + pwd + "Email" + mail);
                        //window.open('index.php', '_self');
                    });
                    setTimeout(function(){
                        window.location.reload();
                   }, 3000);
               } else {
                   //alert(result);
                   $("#errRegUE").show().delay(5000).fadeOut();
               }
            });
            //$.post( "./pages/loginUser.php", { UserPasswordLogin:pwd, UserMailLogin:mail }, function(){
                //alert("Password" + pwd + "Email" + mail);
                //window.open('index.php', '_self');
            //});
        } else {
            //alert("Passwords do not match");
            $("#errRegPW").show().delay(5000).fadeOut();
        }
    });
    $('#loginnow').click(function(){
        if (!$.trim($("#inputPassword").val())) {
            emptyPw = true;
        } else {
            emptyPw = false;
            var pwdLogin = $.trim($("#inputPassword").val());
        }
        if (!$.trim($("#inputUser").val())) {
            emptyU = true;
        } else {
            emptyU = false;
            var mailLogin = $.trim($("#inputUser").val());
        }

        
        if($('#remember').is(':checkbox:checked')){
            var chkbox = "yes";
            //alert("checked");
        } else {
            var chkbox = "no";
            //alert("unchecked");
        }
        
        //alert(uname + pwd + mail);
        function isValidEmailAddress(emailAddress) {
            var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
            return pattern.test(emailAddress);
        };
        if(emptyPw){
            //alert("Empty password");
            $("#errLog").show().delay(5000).fadeOut();
        } else if(emptyU){
            //alert("Empty user or email");
            $("#errLog").show().delay(5000).fadeOut();
        } else {
            if(isValidEmailAddress(mailLogin)) {
                $.post( "./pages/loginUser.php", { UserPasswordLogin:pwdLogin, UserMailLogin:mailLogin, ChkBox:chkbox }, function(result){
                    //alert("E: Password " + pwdLogin + " Email " + mailLogin);
                    //window.open('index.php', '_self');
                    if(result === "true") {
                        $("#succLog").show().delay(2000).fadeOut();
                        setTimeout(function(){
                            window.location.reload();
                       }, 3000);
                   } else {
                       $("#errLog").show().delay(5000).fadeOut();
                   }
                });
            } else {
                $.post( "./pages/loginUser.php", { UserPasswordLogin:pwdLogin, UserNameLogin:mailLogin, ChkBox:chkbox }, function(result){
                    //alert("U: Password " + pwdLogin + " Email " + mailLogin);
                    //window.open('index.php', '_self');
                    //alert(result);
                    if(result === "true") {
                        $("#succLog").show().delay(2000).fadeOut();
                        setTimeout(function(){
                            window.location.reload();
                       }, 3000);
                   } else {
                       $("#errLog").show().delay(5000).fadeOut();
                   }
                    /*if (!$(result).length===0) {
                        alert(result);
                    }*/
                });
            }
        }
    });
     
     $('a[href="#Logout"]').click(function(){
         var userID = true;
         $.post( "./pages/logoutUser.php", { submitlogout:userID }, function(){
             window.open('index.php', '_self');
         });
     });
     
 });


/*var chart;
 $(document).ready(function() {
    var cursan = {
    chart: {
        renderTo: 'container',
        defaultSeriesType: 'area',
        marginRight: 10,
        marginBottom: 30
       },
       title: {
        text: 'Average players per world every hour (CEST)',
       },
       subtitle: {
        text: 'www.runetool.com',
       },
       xAxis: {
        categories: ['12AM', '1AM', '2AM', '3AM', '4AM', '5AM', '6AM', '7AM', '8AM', '9AM', '10AM', '11AM', '12PM', '1PM', '2PM', '3PM', '4PM', '5PM', '6PM', '7PM', '8PM', '9PM', '10PM', '11PM']
        //categories: ['12AM', '1AM', '2AM', '3AM', '4AM', '5AM', '6AM', '7AM', '8AM', '9AM', '10AM', '11AM', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23']
       },
       yAxis: {
        title: {
         text: 'Players per world'
        },
        plotLines: [{
         value: 0,
         width: 1,
         color: '#808080'
        }]
       },
       tooltip: {
            crosshairs: true,
            shared: true
       },
       legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'top',
        x: -10,
        y: 30,
        borderWidth: 0
       },           
        plotOptions: {

            series: {
                cursor: 'pointer',  
                marker: {
                    lineWidth: 1
                }
            }
        },

        series: [{
         color: Highcharts.getOptions().colors[2],
         name: 'Players',
                 marker: {
                     fillColor: '#FFFFFF',
                     lineWidth: 3,
                     lineColor: null // inherit from series
                 },
                 dataLabels: {
                     enabled: true,
                     rotation: 0,
                     color: '#666666',
                     align: 'top',
                     x: -10,
                     y: -10,
                     style: {
                         fontSize: '9px',
                         fontFamily: 'Verdana, sans-serif',
                         textShadow: '0 0 0px black'
                     }
                 }
        }],
    }
        
    //Fetch MySql Records
    jQuery.get('./data.php', null, function(tsv) {
    var lines = [];
    traffic = [];
    try {
    // split the data return into lines and parse them
        tsv = tsv.split(/\n/g);
        jQuery.each(tsv, function(i, line) {
        line = line.split(/\t/);
        date = line[0] ;
        amo=parseFloat(line[1].replace(',', ''));
        if (isNaN(amo)) {
            amo = null;
        }
        traffic.push([
            date,
            amo
     ]);
    });
   } catch (e) {  }
   cursan.series[0].data = traffic;
   chart = new Highcharts.Chart(cursan);
  });
 });*/
 
 var siteurl1 = "http://www.runetool.com/rsplayers-bootstrap/index.php"
 var siteurl2 = "http://www.runetool.com/rsplayers-bootstrap/index.php?p=worldstats"
 var chart;

 $(document).ready(function() {
    var cursan = {
    chart: {
        renderTo: 'container1',
        defaultSeriesType: 'area',
        marginRight: 10,
        marginBottom: 30
       },
       credits: {
          enabled: false
       },
       title: {
        text: 'Online players per hour (CET)',
       },
       subtitle: {
        text: 'www.runetool.com',
       },
       xAxis: {
        categories: ['12AM', '1AM', '2AM', '3AM', '4AM', '5AM', '6AM', '7AM', '8AM', '9AM', '10AM', '11AM', '12PM', '1PM', '2PM', '3PM', '4PM', '5PM', '6PM', '7PM', '8PM', '9PM', '10PM', '11PM']
        //categories: ['12AM', '1AM', '2AM', '3AM', '4AM', '5AM', '6AM', '7AM', '8AM', '9AM', '10AM', '11AM', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23']
       },
       yAxis: {
        title: {
         text: 'Players online'
        },
        plotLines: [{
         value: 0,
         width: 1,
         color: '#808080'
        }]
       },
       tooltip: {
            crosshairs: true,
            shared: true
       },
       legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'top',
        x: -10,
        y: 30,
        borderWidth: 0
       },           
        plotOptions: {

            series: {
                cursor: 'pointer',  
                marker: {
                    lineWidth: 1
                }
            }
        },

        series: [{
         color: Highcharts.getOptions().colors[0],
         name: 'Players',
                 marker: {
                     fillColor: '#FFFFFF',
                     lineWidth: 3,
                     lineColor: null // inherit from series
                 },
                 dataLabels: {
                     enabled: true,
                     rotation: 0,
                     color: '#666666',
                     align: 'top',
                     x: -10,
                     y: -10,
                     style: {
                         fontSize: '9px',
                         fontFamily: 'Verdana, sans-serif',
                         textShadow: '0 0 0px black'
                     }
                 }
        }],
    }
        
    //Fetch MySql Records
    jQuery.get('./pages/data1.php', null, function(tsv) {
    var lines = [];
    traffic = [];
    try {
    // split the data return into lines and parse them
        tsv = tsv.split(/\n/g);
        jQuery.each(tsv, function(i, line) {
        line = line.split(/\t/);
        date = line[0] ;
        amo=parseFloat(line[1].replace(',', ''));
        if (isNaN(amo)) {
            amo = null;
        }
        traffic.push([
            date,
            amo
     ]);
    });
   } catch (e) {  }
   cursan.series[0].data = traffic;
   chart = new Highcharts.Chart(cursan);
  });
 });
 
 
 
 ///////////////////////////////////////////////graph
 
 
 var chart;
 $(document).ready(function() {
    var cursan = {
    chart: {
        renderTo: 'container3',
        defaultSeriesType: 'area',
        marginRight: 10,
        marginBottom: 30
       },
       credits: {
        enabled: false
       },
       title: {
        text: 'Average players per world every hour (CET)',
       },
       subtitle: {
        text: 'www.runetool.com',
       },
       xAxis: {
        categories: ['12AM', '1AM', '2AM', '3AM', '4AM', '5AM', '6AM', '7AM', '8AM', '9AM', '10AM', '11AM', '12PM', '1PM', '2PM', '3PM', '4PM', '5PM', '6PM', '7PM', '8PM', '9PM', '10PM', '11PM']
        //categories: ['12AM', '1AM', '2AM', '3AM', '4AM', '5AM', '6AM', '7AM', '8AM', '9AM', '10AM', '11AM', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23']
       },
       yAxis: {
        title: {
         text: 'Players per world'
        },
        plotLines: [{
         value: 0,
         width: 1,
         color: '#808080'
        }]
       },
       tooltip: {
            crosshairs: true,
            shared: true
       },
       legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'top',
        x: -10,
        y: 30,
        borderWidth: 0
       },           
        plotOptions: {

            series: {
                cursor: 'pointer',  
                marker: {
                    lineWidth: 1
                }
            }
        },

        series: [{
         color: Highcharts.getOptions().colors[2],
         name: 'Players',
                 marker: {
                     fillColor: '#FFFFFF',
                     lineWidth: 3,
                     lineColor: null // inherit from series
                 },
                 dataLabels: {
                     enabled: true,
                     rotation: 0,
                     color: '#666666',
                     align: 'top',
                     x: -10,
                     y: -10,
                     style: {
                         fontSize: '9px',
                         fontFamily: 'Verdana, sans-serif',
                         textShadow: '0 0 0px black'
                     }
                 }
        }],
    }
        
    //Fetch MySql Records
    jQuery.get('./pages/data.php', null, function(tsv) {
    var lines = [];
    traffic = [];
    try {
    // split the data return into lines and parse them
        tsv = tsv.split(/\n/g);
        jQuery.each(tsv, function(i, line) {
        line = line.split(/\t/);
        date = line[0] ;
        amo=parseFloat(line[1].replace(',', ''));
        if (isNaN(amo)) {
            amo = null;
        }
        traffic.push([
            date,
            amo
     ]);
    });
   } catch (e) {  }
   cursan.series[0].data = traffic;
   chart = new Highcharts.Chart(cursan);
  });
 });