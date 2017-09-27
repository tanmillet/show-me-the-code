function getScrollTop(){
    return  window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop;
}

function getWindowHeight(){
    var w=window,d=document,e=d.documentElement,g= d.body;
    return w.innerHeight || e.clientHeight|| g.clientHeight;
}

function scrollCallback(){
    var nav = document.getElementById( 'nav');

    var scrollY = getScrollTop() + getWindowHeight();
    if ( scrollY < document.body.scrollHeight + 220 ){ //
        nav.style.top = (scrollY - 220).toString() + 'px';
    }
}

function scrollToAnchor(v){
    var scrollTop = getScrollTop();
    var scrollToTop = v;
    var distance = scrollToTop > scrollTop ? scrollToTop - scrollTop : scrollTop - scrollToTop;
    if (distance < 100) {
        scrollTo(0, scrollToTop); return;
    }
    var speed = Math.round(distance / 100);
    if (speed >= 20) speed = 20;
    var step = Math.round(distance / 25);
    var leapY = scrollToTop > scrollTop ? scrollTop + step : scrollTop - step;
    var timer = 0;
    if (scrollToTop > scrollTop) {
        for ( var i=scrollTop; i<scrollToTop; i+=step ) {
            setTimeout("window.scrollTo(0, "+leapY+")", timer * speed);
            leapY += step; if (leapY > scrollToTop) leapY = scrollToTop; timer++;
        } return;
    }
    for ( var i=scrollTop; i>scrollToTop; i-=step ) {
        setTimeout("window.scrollTo(0, "+leapY+")", timer * speed);
        leapY -= step; if (leapY < scrollToTop) leapY = scrollToTop; timer++;
    }
}

this.tablecloth = function(){

    // CONFIG

    // if set to true then mouseover a table cell will highlight entire column (except sibling headings)
    var highlightCols = true;

    // if set to true then mouseover a table cell will highlight entire row	(except sibling headings)
    var highlightRows = false;

    // if set to true then click on a table sell will select row or column based on config
    var selectable = true;

    // this function is called when
    // add your own code if you want to add action
    // function receives object that has been clicked
    this.clickAction = function(obj){


    };



    var tableover = false;
    this.start = function(){
        var tables = document.getElementsByClassName('scoresTable');
        for (var i=0;i<tables.length;i++){
            tables[i].onmouseover = function(){tableover = true};
            tables[i].onmouseout = function(){tableover = false};
            rows(tables[i]);
        };
    };

    this.rows = function(table){
        var css = "";
        var tr = table.getElementsByTagName("tr");
        for (var i=0;i<tr.length;i++){
            css = (css == "odd") ? "even" : "odd";
            tr[i].className = css;
            var arr = new Array();
            for(var j=0;j<tr[i].childNodes.length;j++){
                if(tr[i].childNodes[j].nodeType == 1) arr.push(tr[i].childNodes[j]);
            };
            for (var j=0;j<arr.length;j++){
                arr[j].row = i;
                arr[j].col = j;
                if(arr[j].innerHTML == "&nbsp;" || arr[j].innerHTML == "") arr[j].className += " empty";
                arr[j].css = arr[j].className;
                arr[j].onmouseover = function(){
                    over(table,this,this.row,this.col);
                };
                arr[j].onmouseout = function(){
                    out(table,this,this.row,this.col);
                };
                arr[j].onmousedown = function(){
                    down(table,this,this.row,this.col);
                };
                arr[j].onmouseup = function(){
                    up(table,this,this.row,this.col);
                };
                arr[j].onclick = function(){
                    click(table,this,this.row,this.col);
                };
            };
        };
    };

    // appyling mouseover state for objects (th or td)
    this.over = function(table,obj,row,col){
        if (!highlightCols && !highlightRows) obj.className = obj.css + " over";
        if(check1(obj,col)){
            if(highlightCols) highlightCol(table,obj,col);
            if(highlightRows) highlightRow(table,obj,row);
        };
    };
    // appyling mouseout state for objects (th or td)
    this.out = function(table,obj,row,col){
        if (!highlightCols && !highlightRows) obj.className = obj.css;
        unhighlightCol(table,col);
        unhighlightRow(table,row);
    };
    // appyling mousedown state for objects (th or td)
    this.down = function(table,obj,row,col){
        obj.className = obj.css + " down";
    };
    // appyling mouseup state for objects (th or td)
    this.up = function(table,obj,row,col){
        obj.className = obj.css + " over";
    };
    // onclick event for objects (th or td)
    this.click = function(table,obj,row,col){
        if(check1){
            if(selectable) {
                unselect(table);
                if(highlightCols) highlightCol(table,obj,col,true);
                if(highlightRows) highlightRow(table,obj,row,true);
                document.onclick = unselectAll;
            }
        };
        clickAction(obj);
    };

    this.highlightCol = function(table,active,col,sel){
        var css = (typeof(sel) != "undefined") ? "selected" : "over";
        var tr = table.getElementsByTagName("tr");
        for (var i=0;i<tr.length;i++){
            var arr = new Array();
            for(j=0;j<tr[i].childNodes.length;j++){
                if(tr[i].childNodes[j].nodeType == 1) arr.push(tr[i].childNodes[j]);
            };
            var obj = arr[col];
            if (check2(active,obj) && check3(obj)) obj.className = obj.css + " " + css;
        };
    };
    this.unhighlightCol = function(table,col){
        var tr = table.getElementsByTagName("tr");
        for (var i=0;i<tr.length;i++){
            var arr = new Array();
            for(j=0;j<tr[i].childNodes.length;j++){
                if(tr[i].childNodes[j].nodeType == 1) arr.push(tr[i].childNodes[j])
            };
            var obj = arr[col];
            if(check3(obj)) obj.className = obj.css;
        };
    };
    this.highlightRow = function(table,active,row,sel){
        var css = (typeof(sel) != "undefined") ? "selected" : "over";
        var tr = table.getElementsByTagName("tr")[row];
        for (var i=0;i<tr.childNodes.length;i++){
            var obj = tr.childNodes[i];
            if (check2(active,obj) && check3(obj)) obj.className = obj.css + " " + css;
        };
    };
    this.unhighlightRow = function(table,row){
        var tr = table.getElementsByTagName("tr")[row];
        for (var i=0;i<tr.childNodes.length;i++){
            var obj = tr.childNodes[i];
            if(check3(obj)) obj.className = obj.css;
        };
    };
    this.unselect = function(table){
        tr = table.getElementsByTagName("tr")
        for (var i=0;i<tr.length;i++){
            for (var j=0;j<tr[i].childNodes.length;j++){
                var obj = tr[i].childNodes[j];
                if(obj.className) obj.className = obj.className.replace("selected","");
            };
        };
    };
    this.unselectAll = function(){
        if(!tableover){
            tables = document.getElementsByClassName('scoresTable');
            for (var i=0;i<tables.length;i++){
                unselect(tables[i])
            };
        };
    };
    this.check1 = function(obj,col){
        return (!(col == 0 && obj.className.indexOf("empty") != -1));
    }
    this.check2 = function(active,obj){
        return (!(active.tagName == "TH" && obj.tagName == "TH"));
    };
    this.check3 = function(obj){
        return (obj.className) ? (obj.className.indexOf("selected") == -1) : true;
    };

    start();

};

var PaperTemplate = function( fullScore, quesType, domains){
    this._quesTypeTemplate = JSON.parse( quesType );
    this._domainAnalysisTemplate = JSON.parse( domains );

    this._questionTypeIndexes = null;
    this._domainAnalysisIndexes = null;

    this._fullScore = fullScore;

    PaperTemplate.prototype.initTemplate = function(){
        this._questionTypeIndexes = this._quesTypeTemplate["index"];
        this._domainAnalysisIndexes = this._domainAnalysisTemplate["index"];
    }

    PaperTemplate.prototype.isValid = function(){
        return this._quesTypeTemplate != null
            && this._domainAnalysisTemplate != null;
    }
}

var Scores = function( paperTemplate, score, selfDetailString, schoolDetailString, regionDetailString ){
    this._selfScore = score;
    this._paperTemplate = paperTemplate;

    this._selfDetail = JSON.parse( selfDetailString );

    this._schoolDetail = JSON.parse( schoolDetailString );
    this._regionDetail = JSON.parse( regionDetailString );

    Scores.shared_scoreLabels = [ '本题分数', '学校平均成绩', '全省平均成绩', '您的成绩'];
    Scores.shared_domainLabels = ['题数', '您学校平均答对题数','全省平均答对题数', '您答对的题数'];
    Scores.shared_mapNames = ['0', '1', '2', '3'];

    Scores.prototype.getMappedSumScore = function( avgScoreOfSchool, avgScoreOfRegion ){
        var scores = {};

        scores["label"] = "总分";
        scores[Scores.shared_mapNames[0]] = ( this._paperTemplate._fullScore );

        scores[Scores.shared_mapNames[1]] = ( avgScoreOfSchool );
        scores[Scores.shared_mapNames[2]] = ( avgScoreOfRegion );

        scores[Scores.shared_mapNames[3]] = ( this._selfScore );
        return scores;
    }

    Scores.prototype.getMappedQuestionTypeScoreDataset = function(){
        var scoreDataset = [];

        var avgScoreOfSchool = 0;
        var avgScoreOfRegion = 0;

        var quesTypeIndexes = this._paperTemplate._questionTypeIndexes;
        for (var eachIndex = 0; eachIndex < quesTypeIndexes.length; ++eachIndex ){
            var scores = {};
            scores["label"] = quesTypeIndexes[eachIndex];

            scores[Scores.shared_mapNames[0]] = ( this._paperTemplate._quesTypeTemplate[quesTypeIndexes[eachIndex]]["score"]);
            scores[Scores.shared_mapNames[3]] = ( this._selfDetail[quesTypeIndexes[eachIndex]]["score"] );

            var schoolAvgScore = this._schoolDetail[quesTypeIndexes[eachIndex]]["avgscore"] / this._schoolDetail["totalcount"];
            avgScoreOfSchool += schoolAvgScore;

            var regionAvgScore = this._regionDetail[quesTypeIndexes[eachIndex]]["avgscore"]  / this._regionDetail["totalcount"];
            avgScoreOfRegion += regionAvgScore;

            scores[Scores.shared_mapNames[1]] = ( schoolAvgScore );
            scores[Scores.shared_mapNames[2]] = ( regionAvgScore );

            scoreDataset.push( scores );
        }

        scoreDataset.push( this.getMappedSumScore( avgScoreOfSchool,avgScoreOfRegion ) );
        return scoreDataset;
    }

    Scores.prototype.getMappedSumDomainCount = function( counts ){
        var domains = {};

        domains["label"] = "总题数";
        for (var _ = 0; _ < Scores.shared_mapNames.length; ++_ ){
            domains[Scores.shared_mapNames[_]] = counts[_];
        }
        return domains;
    }

    Scores.prototype.getMappedDomainAnalysisDataset = function(){
        var dataset = [];
        var domainQuesCounts = [0, 0, 0, 0];

        var domainAnalyIndexes = this._paperTemplate._domainAnalysisIndexes;
        for (var eachIndex = 0; eachIndex < domainAnalyIndexes.length; ++eachIndex ){

            var domains = {};
            domains["label"] = domainAnalyIndexes[eachIndex];

            var paperDomainCount = this._paperTemplate._domainAnalysisTemplate[domainAnalyIndexes[eachIndex]]["count"];
            domainQuesCounts[0] += paperDomainCount;
            domains[Scores.shared_mapNames[0]] = paperDomainCount;

            var selfDomainCount = this._selfDetail[domainAnalyIndexes[eachIndex]]["count"];
            domainQuesCounts[3] += selfDomainCount;
            domains[Scores.shared_mapNames[3]] = selfDomainCount;

            var schoolAvgCount = this._schoolDetail[domainAnalyIndexes[eachIndex]]["avgcount"] / this._schoolDetail["totalcount"];
            domainQuesCounts[1] += schoolAvgCount;
            domains[Scores.shared_mapNames[1]] = ( schoolAvgCount );

            var regionAvgScore = this._regionDetail[domainAnalyIndexes[eachIndex]]["avgcount"] / this._regionDetail["totalcount"];
            domainQuesCounts[2] += regionAvgScore;
            domains[Scores.shared_mapNames[2]] = ( regionAvgScore );

            dataset.push( domains );
        }

        dataset.push( this.getMappedSumDomainCount( domainQuesCounts ) );
        return dataset;
    }
}

function appendQuestionRows(table, labels, dataset, count){
    // other rows
    // 1 -> correct answers
    // 2 -> your answers
    // 3 -> domain
    var classes = ['scoresCell', 'scoresCell', 'yourScoresCell', 'domainCell'];
    //// hard coding
    var yourScoreCellIndex = 2;

    for ( var rowIndex = 1; rowIndex < labels.length; ++rowIndex ){
        var rowElement = document.createElement('tr');
        var firstElement = document.createElement('th');
        firstElement.innerHTML = labels[rowIndex];
        firstElement.className = 'scoresTitle';
        rowElement.appendChild( firstElement );

        var eachValues = dataset[rowIndex - 1];

        if ( rowIndex == yourScoreCellIndex ){
            for ( var colIndex = 0; colIndex < count; ++colIndex ){
                var eachElement = document.createElement('td');

                var value = eachValues[colIndex];
                eachElement.innerHTML = value;
                if ( value == 0 || value.toUpperCase() == 'F'){
                    eachElement.className = "yourFailedScoresCell";
                } else{
                    eachElement.className = classes[rowIndex];
                }
                rowElement.appendChild( eachElement );
            }
        } else{
            for ( var colIndex = 0; colIndex < count; ++colIndex ){
                var eachElement = document.createElement('td');

                eachElement.innerHTML = eachValues[colIndex];
                eachElement.className = classes[rowIndex];
                rowElement.appendChild( eachElement );
            }
        }
        table.appendChild( rowElement );
    }
}

function appendQuestionNumberRow(table, label, rangeStart, rangeEnd){
    // the first row for index
    var rowElement = document.createElement('tr');
    var firstNumElement = document.createElement('th');
    firstNumElement.innerHTML = label;
    firstNumElement.className = 'scoresTitle';
    rowElement.appendChild( firstNumElement );

    for ( var eachNum = rangeStart; eachNum < rangeEnd; ++eachNum ){
        var numElement = document.createElement('th');
        numElement.innerHTML = eachNum.toString();
        numElement.className = 'scoresTitle';
        rowElement.appendChild( numElement );
    }

    table.appendChild( rowElement );
}

function LoadTable(score){
    var tableWrapper = document.getElementById('tableWrapper');
    //

    var indexes = score._paperTemplate._questionTypeIndexes;
    var domainTemplate = score._paperTemplate._domainAnalysisTemplate;
    for ( var eachIndex = 0; eachIndex < indexes.length; ++eachIndex ){
        var templateIndex = indexes[eachIndex];

        var questionTypeDetail = score._paperTemplate._quesTypeTemplate[ templateIndex ];

        var range = getRange( questionTypeDetail );
        var rangeStart = range['start'];
        var rangeEnd = range['end'];

        if ( rangeStart < rangeEnd ){
            var labels = questionTypeDetail["labels"].split(',');
            // correct answers
            var correctAnswers = questionTypeDetail["values"].split(',');

            // your answers
            var urAnswers = score._selfDetail[templateIndex]["print"];

            // domains
            var domains = questionTypeDetail["domains"].split(',');

            // get domains value
            var domainValues = [];
            for ( var index = 0; index < domains.length; ++index ){
                var domain = domains[ index ];
                domainValues.push( domainTemplate[ domain ][ "name" ] );
            }

            // generate the table
            var tableTitle = document.createElement('div');
            tableTitle.innerHTML = "<a>" + (eachIndex + 1) + "、" + templateIndex + "</a>";
            tableTitle.className = "answerTitle tableTitle";

            var table = document.createElement('table');
            table.className = 'scoresTable';

            appendQuestionNumberRow( table, labels[0], rangeStart, rangeEnd );

            var values = [correctAnswers, urAnswers, domainValues];
            appendQuestionRows( table, labels, values, rangeEnd - rangeStart );

            var wrapper = document.createElement( 'div' );
            wrapper.className = "tableInnerWrapper";
            wrapper.appendChild( table );

            tableWrapper.appendChild( tableTitle );
            tableWrapper.appendChild( wrapper );
        }
    }
}

function getRange( questionTypeDetail ){
    var ranges = questionTypeDetail["range"].split('-');

    var rangeStart = 1;
    var rangeEnd = 0;
    if ( ranges.length == 2 ){
        rangeStart = parseInt(ranges[0]);
        rangeEnd = parseInt( ranges[1] ) + 1;
    }

    if ( isNaN( rangeStart ) || isNaN( rangeEnd )
        || rangeEnd <= rangeStart ){
        // read count
        // count from 1 - count + 1
        rangeStart = 1;
        rangeEnd = parseInt(questionTypeDetail["count"]) + 1;
    }

    return { 'start' : rangeStart, 'end' : rangeEnd };
}

function LoadQuesTypeScoreTableColumn( dataset ){
    var indexes = score._paperTemplate._questionTypeIndexes;

    // update scores labels
    var sum = dataset[ indexes.length ][ Scores.shared_mapNames[0] ];
    // update columns
    var urScore = document.getElementById( 'yourScore' );
    var urScoreValue = dataset[ indexes.length ][ Scores.shared_mapNames[3] ];
    urScore.firstElementChild.innerHTML = urScoreValue;
    urScore.style.width = ( urScore.offsetWidth + ( urScoreValue / sum ) * 160 ) + "px";
    urScore.style.backgroundColor = "#ff7d1a";

    var schoolAvgScore = document.getElementById( 'schoolAvgScore' );
    var schoolAvgValue = dataset[ indexes.length ][ Scores.shared_mapNames[1] ];
    schoolAvgScore.firstElementChild.innerHTML = schoolAvgValue.toFixed( 2 );
    schoolAvgScore.style.width = ( schoolAvgScore.offsetWidth + ( schoolAvgValue / sum ) * 160 ) + "px";
    schoolAvgScore.style.backgroundColor = "#3d8cff";

    var allAvgScore = document.getElementById( 'allAvgScore' );
    var allAvgValue = dataset[ indexes.length ][ Scores.shared_mapNames[2] ];
    allAvgScore.firstElementChild.innerHTML = allAvgValue.toFixed( 2 );
    allAvgScore.style.width = ( allAvgScore.offsetWidth + ( allAvgValue / sum ) * 160 ) + "px";
    allAvgScore.style.backgroundColor = "#1FC20E";

    //
    var tableWrapper = document.getElementById('quesTypeTable');
    var table = document.createElement( 'table' );
    table.className = "scoresTable";

    // first row
    var titleRow = document.createElement('tr');
    var titleInerlHTML = "<th class='scoresTitle'>题目类型</th>";
    for ( var col = 0; col < Scores.shared_scoreLabels.length; ++col ){
        titleInerlHTML += "<th class='scoresTitle'>" + Scores.shared_scoreLabels[ col ] + "</th> ";
    }
    titleRow.innerHTML = titleInerlHTML;

    table.appendChild( titleRow );

    // data rows
    // classes
    var cellClasses = [ "fullScoreCell", "schoolAvgCell", "regionAvgCell", "urCell" ];
    for ( var eachIndex = 0; eachIndex < ( indexes.length + 1 ); ++eachIndex ){
        var row = document.createElement( 'tr' );

        var title = document.createElement( 'th' );
        title.className = "scoresTitle";
        title.innerHTML = ( eachIndex == indexes.length ? "您的总分" : indexes[ eachIndex ]);
        row.appendChild( title );

        for ( var col = 0; col < Scores.shared_scoreLabels.length; ++col ){
            var td = document.createElement('td');
            td.className = cellClasses[col];
            td.innerHTML = col > 0 && col < ( Scores.shared_scoreLabels.length - 1 ) ?
                dataset[eachIndex][col].toFixed( 2 ) : dataset[eachIndex][col];

            row.appendChild( td );
        }
        table.appendChild( row );
    }

    tableWrapper.appendChild( table );
}

/////######################
var gShared_accuracy = [];
var gShared_domainNames = [];
function LoadDomainTypeTableColumn( dataset ){
    //var indexes = score._paperTemplate._questionTypeIndexes;
    var indexes = score._paperTemplate._domainAnalysisIndexes;

    var sum = dataset[ indexes.length ][ Scores.shared_mapNames[0] ];
    // update columns
    var urdomain = document.getElementById( 'yourDomain' );
    var urDomainValue = dataset[ indexes.length ][ Scores.shared_mapNames[3] ];
    urdomain.firstElementChild.innerHTML = urDomainValue;
    urdomain.style.width = ( urdomain.offsetWidth + ( urDomainValue / sum ) * 160 ) + "px";
    urdomain.style.backgroundColor = "#ff7d1a";

    var schoolAvgDomain = document.getElementById( 'schoolAvgDomain' );
    var schoolAvgValue = dataset[ indexes.length ][ Scores.shared_mapNames[1] ];
    schoolAvgDomain.firstElementChild.innerHTML = schoolAvgValue.toFixed( 2 );
    schoolAvgDomain.style.width = ( schoolAvgDomain.offsetWidth + ( schoolAvgValue / sum ) * 160 ) + "px";
    schoolAvgDomain.style.backgroundColor = "#3d8cff";

    var allAvgDomain = document.getElementById( 'allAvgDomain' );
    var allAvgValue = dataset[ indexes.length ][ Scores.shared_mapNames[2] ];
    allAvgDomain.firstElementChild.innerHTML = allAvgValue.toFixed( 2 );
    allAvgDomain.style.width = ( allAvgDomain.offsetWidth + ( allAvgValue / sum ) * 160 ) + "px";
    allAvgDomain.style.backgroundColor = "#1FC20E";

    ////
    // update table
    var tableWrapper = document.getElementById('domainTable');
    var table = document.createElement( 'table' );
    table.className = "scoresTable";

    // Accuracy calculate
    var formatedAccuracy = [];
    for ( var index =0; index < indexes.length; ++index ){
        var domainValues = dataset[ index ];

        var eachDomainValue = domainValues[  Scores.shared_mapNames[0] ];
        if ( eachDomainValue == 0 ){
            gShared_accuracy.push( 0 );
            formatedAccuracy.push( "/" );
        } else{
            var eachAcuracy = 100 * domainValues[  Scores.shared_mapNames[3] ] / domainValues[  Scores.shared_mapNames[0] ];
            gShared_accuracy.push( eachAcuracy );
            formatedAccuracy.push( (eachAcuracy ).toFixed( 1 ) + '%' );
        }
    }

    for ( var index = 0; index < indexes.length; ++index ){
        gShared_domainNames.push( score._paperTemplate._domainAnalysisTemplate[ indexes[ index ] ][ "name" ] );
    }

    // first row
    var titleRow = document.createElement('tr');
    var titleInerlHTML = "<th class='scoresTitle'>本次试卷领域分析</th>";
    for ( var col = 0; col < Scores.shared_domainLabels.length; ++col ){
        titleInerlHTML += "<th class='scoresTitle'>" + Scores.shared_domainLabels[ col ] + "</th> ";
    }
    // last
    titleInerlHTML += "<th class='scoresTitle'>正确率</th> ";
    titleRow.innerHTML = titleInerlHTML;

    table.appendChild( titleRow );

    // data rows
    var cellClasses = [ "fullScoreCell", "schoolAvgCell", "regionAvgCell", "urCell", "urCell" ];
    for ( var eachIndex = 0; eachIndex < ( indexes.length + 1 ); ++eachIndex ){
        var row = document.createElement( 'tr' );

        var eachRowInnerHTML = "<th class='scoresTitle'>" + ( eachIndex == indexes.length ? "总题数" : gShared_domainNames[ eachIndex ]) + "</th>";
        var colCount = Scores.shared_domainLabels.length + 1;
        for ( var col = 0; col < colCount; ++col ){
            if ( col == 0 || col == ( colCount - 2 ) ){
                eachRowInnerHTML += "<td class='" + cellClasses[col]  + "'>" + dataset[eachIndex][col] + "</td>";
            } else if ( col == ( colCount - 1 ) ){
                // correct rate
                if ( eachIndex < indexes.length ){
                    eachRowInnerHTML += "<td class='" + cellClasses[col]  + "'>" + formatedAccuracy[ eachIndex ] + "</td>";
                } else{
                    eachRowInnerHTML += "<td class='" + cellClasses[col]  + "'></td>";
                }
            } else{
                eachRowInnerHTML += "<td class='" + cellClasses[col]  + "'>" + dataset[eachIndex][col].toFixed(2) + "</td>";
            }
        }
        row.innerHTML = eachRowInnerHTML;
        table.appendChild( row );
    }

    tableWrapper.appendChild( table );
}
