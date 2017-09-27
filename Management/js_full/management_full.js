
function uploadPaperPageSwitchedIn( index ){
    var page = $( $( '.page' )[ index ] );
    page.children( '.notice' ).fadeIn( 600 );
}

function uploadPaperPageSwitchedOut( index ){
    var page = $( $( '.page' )[ index ] );
    page.children( '.notice' ).fadeOut( 600 );
}

function queryPanelSwitchedIn( index ){
    $( '.bottomActionBox').animate( { bottom: "0px" }, 500 );
}

function queryPanelSwitchedOut( index ){
    $( '.bottomActionBox').animate( { bottom: "-40px" }, 500 );

}

var curElement = null;
var switchInCallbacks = [ uploadPaperPageSwitchedIn, uploadPaperPageSwitchedIn,  uploadPaperPageSwitchedIn, queryPanelSwitchedIn ];
var switchOutCallbacks = [ uploadPaperPageSwitchedOut, uploadPaperPageSwitchedOut, uploadPaperPageSwitchedOut, queryPanelSwitchedOut ];

function onClickedLeftListItem(){
    if ( !curElement.is( $(this) ) ){
        // current element switched out
        var currentIndex = parseInt( curElement.attr('ref') );
        {
            var outCallback = switchOutCallbacks[ currentIndex ];
            if ( outCallback != null ){
                outCallback( currentIndex );
            }
        }

        // update imgs
        var img = curElement.children( 'img' );
        var src= img.attr( 'src' );
        var newSrcIndex = src.lastIndexOf('_act.png');
        img.attr( 'src', src.substring( 0, newSrcIndex ) + '.png' );

        curElement.removeClass( 'currentPage' );
        $(this).addClass( 'currentPage' );
        curElement = $(this);

        // update self
        var img = curElement.children('img');
        var src = img.attr('src');
        if ( src.lastIndexOf( '_act.png' ) == -1 ){
            var extPos = src.lastIndexOf('.');
            img.attr( 'src', src.substr( 0, extPos ) + '_act.png' );
        }

        // next element switched in
        var index = parseInt( $(this).attr('ref') );
        {
            var inCallback = switchInCallbacks[ index ];
            if ( inCallback != null ){
                inCallback( index );
            }
        }

        $('.listPages').animate( {'scrollTop': pagesTop[ index ]}, 500);
    }
}

var pagesTop = [ 0, 0, 0, 0 ];
var LeftColumnTop = [ 0, 0 ];
function updatePagesTop(){
    var pages = $( '.page' );
    for ( var index = 1; index < pages.length; ++index ){
        pagesTop[ index ] = pagesTop[ index - 1 ] +  pages[ index ].scrollHeight;
    }
}

function updateLeftColumnTop(){
    // ### 50 fixed size
    LeftColumnTop[ 0 ] = $( '.leftColumn[ref="0"]' ).height();
}

var resizeTimeout = null;
var isHoveredSearchDropdown = false;

// global table
// for constructing query table and its UI
var gShared_table = null;
var gShared_contestantEdit = null;
var gShared_paperTemplateSet = null;
var gShared_actionManager = null;
var gShared_exporter = null;
$('body').ready( function(){

    curElement = $( '.headTabInner :first' );
    curElement.addClass( 'currentPage' );
    var subimg = $('.headTabInner img:first');
    var extPos =  subimg.attr('src').lastIndexOf('.');
    subimg.attr( 'src', subimg.attr('src').substr( 0, extPos ) + '_act.png' );

    $('.headTabInner').click( onClickedLeftListItem );
    $('.headTabInner').hover( function(){

        if ( !$(this).is( curElement ) ){
            var img = $(this).children('img');
            var src = img.attr('src');
            var extPos = src.lastIndexOf('.');
            img.attr( 'src', src.substr( 0, extPos ) + '_act.png' );
        }
    }, function(){
        if ( !$(this).is( curElement ) ){
            var img = $(this).children('img');
            var src= img.attr('src');
            var newSrcIndex = src.indexOf('_act.png');
            img.attr('src', src.substring( 0, newSrcIndex ) + '.png' );
        }
    });

    $('.fileAdd').click( function(){
        $(this).parent().parent().find('input').click();
    } );

    $( '.userAction').click( function(){
        $( '.dropDownUserAction').toggle();
    } );

    $( window ).resize( function (){
        if ( resizeTimeout ) {
            clearTimeout( resizeTimeout );
        }
        resizeTimeout = setTimeout( onWindowResized, 500 );
    } );

    $( '.search'  ).focus( function(){
        $( '.searchHelperDropDown' ).fadeIn( 200 );
    } );
    $( '.searchHelperDropDown').hover( function(){ isHoveredSearchDropdown = true; }, function(){
        if ( !( $( '.search').is( ':focus') ) ){
            $( this ).fadeOut( 200 );
        }
        isHoveredSearchDropdown = false;
    });
    $( '.search'  ).blur( function(){
        if ( !isHoveredSearchDropdown ){
            $( '.searchHelperDropDown' ).fadeOut( 200 );
        }
    } );

    $( '.searchSubmit' ).click( onSearch );
    $('.sctl').keyup(function( event ){
        if( event.keyCode == 13 ){
            onSearch();
        }
    });

    $( '.templateDownload').click( function(){
        window.open( $( this ).attr( 'href' ) );
    } );

    $( '.exit').click( function(){
        window.location.href="./ManagementLogin.php";
    } );

    updatePagesTop();
    updateLeftColumnTop();

    initFileUpload();
    initPanelAction();

    gShared_table = new QueryTable();
    gShared_contestantEdit = new ContestantEdit();

    gShared_paperTemplateSet = new PaperTemplateSet();
    gShared_actionManager = new ActionManager();

    gShared_exporter = new Exporter();
});

function onWindowResized(){
    if ( curElement != null ){
        updatePagesTop();
        var index = parseInt( curElement.attr('ref') );

        if ( index > 0 ){
            $('.listPages').animate( {'scrollTop': pagesTop[ index ]}, 200);
        }
        $( '.containerMask').animate( {width: '100%', height: '100%'}, 200 );
    }
}


function updateProgress( progressBar, progress ){
    var progressBarWidth = ( progress / 100 ) * 98;
    progressBar.children('.progress').css('width', progressBarWidth + '%' );
}

function formatFileSize( bytes ) {
    if (typeof bytes !== 'number') {
        return '';
    }
    if (bytes >= 1048576) {
        return (bytes / 1048576).toFixed(2) + ' MB';
    }
    return (bytes / 1024).toFixed(2) + ' KB';
}

// upload
function initFileUpload(){
    $( '.uploadOK').click( function(){
        $( '.uploadProgressBox').fadeOut( 1000, function(){
            $( '.containerMask').fadeOut( 1000 );
            $( '.uploadProgressBox' ).hide();
            $( '.uploadResultMessage').hide();
            $( '.uploadOK').hide();
        } );
    })

    var OptGenerator = function( ref ){
        return {
            singleFileUploads: true,
            add: function (e, data)
            {
                var reg = new RegExp('^.+\\.xlsx$');
                if ( reg.test( data.files[0].name ) ){
                    if ( data.files[0].size < 262144 ){
                        $( '.containerMask').css('background','rgba(0, 0, 0, 0.65)');
                        $( '.containerMask').fadeIn( 1000, function(){
                            $( '.uploadOK').hide();
                            $('.uploadResultMessage.a:first').html("文件处理可能需要一些时间,请稍等。");

                            $( '.uploadProgressBox').fadeIn();

                            $( '.actionLabel' ).html( "正在上传文件:" );
                            $( '.fileLabel' ).html( data.files[0].name );
                            $( '.fileSizeLabel' ).html( formatFileSize( data.files[0].size ) );

                            updateProgress( $('.progressBar'), 0 );

                            var jqXHR = data.submit();
                        } );

                    } else{
                        $( '.containerMask').css('background','rgba(0, 0, 0, 0.65)');
                        $( '.containerMask').fadeIn( 1000, function(){
                            // post exceed max size
                            $( '.uploadProgressBox').fadeIn();
                            $( '.actionLabel' ).html( "上传文件失败:" );
                            $('.uploadResultMessage').fadeIn( function(){
                                $('.uploadResultMessage a:first').html( "文件太大，请将数据分成数个小文件分别上传。" );
                            });
                            $( '.uploadOK').fadeIn();
                        });
                    }
                } else{
                    $( '.containerMask').css('background','rgba(0, 0, 0, 0.65)');
                    $( '.containerMask').fadeIn( 1000, function(){
                        $( '.uploadProgressBox').fadeIn();
                        $( '.actionLabel' ).html( "上传文件失败:" );
                        $('.uploadResultMessage').fadeIn( function(){
                            $('.uploadResultMessage a:first').html( "文件格式错误，必须为xlsx的Excel文档。" );
                        });
                        $( '.uploadOK').fadeIn();
                    });
                }
            },

            done: function(e, data){
                $( '.actionLabel' ).html( "处理文件完成:" );
                $('.uploadResultMessage').show();
                if ( data.result == null || data.result == "" ){
                    $('.uploadResultMessage a:first').html( "处理失败，请尝试重新上传" );
                    return;
                }

                var obj = JSON.parse( data.result );
                var message = null;
                if ( $.isPlainObject(obj) && obj["result"] == "success" ){
                    message = "处理成功";
                } else{
                    message = "处理失败，请尝试重新上传";
                }

                if ( $.isPlainObject(obj) && "message" in obj ){
                    message += "，" + obj["message"];
                }
                $('.uploadResultMessage a:first').html( message );
            },

            progress: function(e, data){
                var progress = parseInt(data.loaded / data.total * 100, 10);
                updateProgress( $('.progressBar'), progress );

                if(progress == 100){
                    $( '.actionLabel' ).html( "正在解析文件:" );
                }
            },

            fail:function(e, data){
                $( '.actionLabel' ).html( "上传文件失败:" );
            },

            always: function(){
                $( '.uploadOK').fadeIn();
            }
        };
    }

    $( '.paperUploader' ).fileupload( OptGenerator( '0') );
    $( '.scoreUploader' ).fileupload( OptGenerator( '1') );
    $( '.commentUploader' ).fileupload( OptGenerator( '2') );
}

var Contestant = function( jsonData ){
    this._id = jsonData[ 'applyID' ];
    this._name = jsonData[ 'name' ];
    this._grade = jsonData[ 'grade' ];
    this._school = jsonData[ 'school' ];
    this._score = jsonData[ 'score' ];
    this._paperID = jsonData[ 'paperID' ];
    this._detail = jsonData[ 'detail' ];

    Contestant.Clone = function( contestant ){
        return JSON.parse( JSON.stringify( contestant ) );
    }
}

var QueryTableDataset = function( loadFromServerAction, queryURL, pageNumber, jsonData ){
    this._dataset = [ pageNumber ]; // contestant
    this._createdTables = [ pageNumber ];   // DOM element
    this._pageNumber = pageNumber;
    this._currentNumber = 0;

    this._loadFromServerAction = loadFromServerAction;
    this._queryURL = queryURL;

    var that = this;

    this.parseData = function( jsonData ){

        if ( jsonData != null && $.isArray( jsonData ) ){
            var correctDataArray = [];
            var correctTables = [];
            var dataTable = $( '.dataRows' );

            for ( var each in jsonData ){
                var contestant = new Contestant( jsonData[ each ] );
                var tableRow = this.createTableRow( contestant );
                var $tableRow = $( tableRow );
                if ( tableRow != null ){
                    $tableRow.appendTo( dataTable );

                    correctDataArray.push( contestant );
                    correctTables.push( tableRow );
                }
            }

            // register event
            ////
            dataTable.find( 'input[type="checkbox"]').click( onSelected );
            dataTable.find( '.inlineEdit' ).click( onInlineRowEdit );
            dataTable.find( '.inlineDelete' ).click( onInlineRowDelete );

            this._dataset[ this._currentNumber ] = correctDataArray;
            this._createdTables[ this._currentNumber ] =  correctTables;
        }
    }

    QueryTableDataset.prototype.askForNextPageData = function( offset ){
        if ( ( this._currentNumber + offset < this._pageNumber ) && ( this._currentNumber + offset >= 0 ) ){
            // go to next page
            this._currentNumber += offset;
            var table = this._createdTables[ this._currentNumber ];
            var rowData = this._dataset[ this._currentNumber ];

            var $dataRows = $( '.dataRows');
            $dataRows.empty();
            if ( table != null ){
                $dataRows.append( table );
                $dataRows.find( 'input[type="checkbox"]').click( onSelected );
                $dataRows.find( '.inlineEdit' ).click( onInlineRowEdit );
                $dataRows.find( '.inlineDelete' ).click( onInlineRowDelete );
            } else if ( $.isArray( rowData ) ){
                this.parseData( rowData );
            }
            else{
                var that = this;
                this._loadFromServerAction( this._queryURL + "&page=" +  this._currentNumber, function( jsonData ){
                    if ( jsonData["result"] === "success"){
                        that.parseData( jsonData["data"] );
                    }
                }, null );
            }
            return true;
        }
        return false;
    }

    QueryTableDataset.prototype.createTableRow = function( contestant ){
        var row = document.createElement( 'tr' );

        var dataString = '<td class="dataCell"><input type="checkbox"></td>\
            <td colspan="2" class="dataCell contestantID">{0}</td>\
            <td class="dataCell contestantName">{1}</td>\
            <td class="dataCell contestantGrade">{2}</td>\
            <td colspan="2" class="dataCell contestantSchool">{3}</td>\
            <td class="dataCell contestantScore">{4}</td>\
            <td class="dataCell"><div class="inlineAction inlineEdit">\
            <img src="img/edit_contestant.png"><a>编辑</a></div>\
            <div class="inlineAction inlineDelete"><img src="img/remove_contestant.png"><a>移除</a></div></td>';

        row.innerHTML = dataString.replace( '{0}', contestant._id ).replace( '{1}', contestant._name )
            .replace( '{2}', contestant._grade )
            .replace( '{3}', contestant._school )
            .replace( '{4}', contestant._score );

        return row;
    }

    if ( jsonData != null ){
        this.parseData( jsonData );
    }
}

QueryTableDataset.prototype.updateContestantRow = function( index, contestant ){
    var currentPageDataset = this._dataset[ this._currentNumber ];
    var currentTable = this._createdTables[ this._currentNumber ];
    if ( currentPageDataset == null
        || currentTable == null
        || index >= currentPageDataset.length
        || index >= currentTable.length){
        return;
    }

    var $row = $( currentTable[ index ] );
    currentPageDataset[ index ] = contestant;

    $row.find( '.contestantID').html( contestant._id );
    $row.find( '.contestantName').html( contestant._name );
    $row.find( '.contestantGrade').html( contestant._grade );
    $row.find( '.contestantSchool').html( contestant._school );
    $row.find( '.contestantScore').html( contestant._score );
}

var NumberedNavigator = function(){
    var maxStep = 5;

    this._currentIndex = 0;
    this._dataset = null;
    this._isBackButtonShown = false;
    this._isForwardButtonShown = false;

    var enableBackButton = function( isEnabled ){
        if ( isEnabled == true ){
            $( '.backButton').removeAttr( 'disabled' );
        } else{
            $( '.backButton').attr('disabled','disabled');
        }
    }

    var enableForwardButton = function( isEnabled ){
        if ( isEnabled == true ){
            $( '.forwardButton').removeAttr( 'disabled' );
        } else{
            $( '.forwardButton').attr('disabled', 'disabled');
        }
    }

    this.setDataset = function( tableDataset ){
        $( '.numbered[index="' + this._currentIndex + '"]').removeClass( 'currentNumbered' );
        this._currentIndex = 0;
        $( '.numbered[index="' + this._currentIndex + '"]').addClass( 'currentNumbered' );

        if ( tableDataset != null ){
            this._dataset = tableDataset;
            this._isBackButtonShown = false;
            this._isForwardButtonShown = tableDataset._pageNumber < maxStep ? false : true;

            this.updateNumeredButtons();

            enableBackButton( this._isBackButtonShown );
            enableForwardButton( this._isForwardButtonShown );
        } else{
            this.updateNumeredButtons();
        }
    }

    this.updateNumeredButtons = function(){
        var startValue = 1;
        var shownButtonNum = 1;
        if ( this._dataset != null ){
            startValue = this._dataset._currentNumber - this._dataset._currentNumber % maxStep + 1;
            shownButtonNum = Math.min( this._dataset._pageNumber - startValue + 1, maxStep );
        }

        for ( var index = 0; index < maxStep; ++index ){
            if ( index < shownButtonNum ){
                var $numbered = $( '.numbered[index="' + index.toString() + '"]');
                $numbered.show();
                $numbered.children( 'a').html( startValue + index )
            } else{
                $( '.numbered[index="' + index.toString() + '"]' ).hide();
            }
        }
    }

    this.determineMaxStepButton = function(){
        if ( this._dataset._currentNumber <= maxStep ){
            if ( this._isForwardButtonShown == false && this._dataset._pageNumber > maxStep ){
                // show the forward button
                enableForwardButton( true );
                this._isForwardButtonShown = true;
            }

            if ( this._isBackButtonShown == true ){
                // hidden back button
                enableBackButton( false );
                this._isBackButtonShown = false;
            }
        } else{
            if ( this._dataset._currentNumber < this._dataset._pageNumber - maxStep ){
                if ( this._isForwardButtonShown == false ){
                    // show the forward button
                    enableForwardButton( true );
                    this._isForwardButtonShown = true;
                }
            } else{
                if ( this._isForwardButtonShown == true ){
                    // hidden forward button
                    enableForwardButton( false );
                    this._isForwardButtonShown = false;
                }
            }

            if ( this._isBackButtonShown == false ){
                // hidden back button
                enableBackButton( false );
                this._isBackButtonShown = true;
            }
        }
    }

    this.canBack = function(){
        var min = Math.min( this._dataset._currentNumber, maxStep );
        return {nextPage: min > this._currentIndex, left:min};
    }

    this.canForward = function(){
        var diff = this._dataset._pageNumber - this._dataset._currentNumber - 1;
        var min = Math.min( diff, maxStep );
        return {nextPage : min >= ( maxStep - this._currentIndex ), left: min};
    }

    this.jumpByOffset = function( offset ){
        if ( offset != 0 && this._dataset != null ){
            if ( offset + this._currentIndex >= 0 && offset < maxStep - this._currentIndex ){
                if ( this._dataset.askForNextPageData( offset ) ){
                    this.determineMaxStepButton();

                    $( '.numbered[index="' + this._currentIndex + '"]').removeClass( 'currentNumbered' );
                    this._currentIndex = this._currentIndex + offset;
                    $( '.numbered[index="' + this._currentIndex + '"]').addClass( 'currentNumbered' );
                }
            }
        }
    }

    this.updateCurrentIndex = function(){
        $( '.numbered[index="' + this._currentIndex + '"]').removeClass( 'currentNumbered' );
        this._currentIndex = Math.min( this._currentIndex, this._dataset._currentNumber % maxStep);
        $( '.numbered[index="' + this._currentIndex + '"]').addClass( 'currentNumbered' );
    }
}

NumberedNavigator.prototype.jumpTo = function( index ){
    if ( index != this._currentIndex && this._dataset != null ){
        var offset = index - this._currentIndex;
        if ( this._dataset.askForNextPageData( offset ) ){
            this.determineMaxStepButton();

            $( '.numbered[index="' + this._currentIndex + '"]').removeClass( 'currentNumbered' );
            this._currentIndex = index;
            $( '.numbered[index="' + this._currentIndex + '"]').addClass( 'currentNumbered' );
        }
    }
}

NumberedNavigator.prototype.back = function(){
    if ( this._dataset != null ){
        var left = this.canBack();
        var leftLeft = left.left;
        if ( left.nextPage && leftLeft > 0 ){
            if ( this._dataset.askForNextPageData( -leftLeft ) ){
                this.determineMaxStepButton();

                this.updateNumeredButtons();
                this.updateCurrentIndex();
            }
        } else{
            if ( leftLeft > 0 ){
                this.jumpByOffset( -leftLeft );
            }
        }
    }
}

NumberedNavigator.prototype.forward = function(){
    if ( this._dataset != null ){
        var right = this.canForward();
        if ( right.nextPage && right.left > 0 ){
            if ( this._dataset.askForNextPageData( right.left ) ){
                this.determineMaxStepButton();

                this.updateNumeredButtons();
                this.updateCurrentIndex();
            }
        } else{
            if ( right.left > 0 ){
                this.jumpByOffset( right.left );
            }
        }
    }
}

var spinner = new Spinner({
    className: 'spinner',
    lines: 8,
    length: 18,
    width: 10,
    color: '#fff'
});

var QueryTable = function(){
    this._numberedNavigator = new NumberedNavigator();
    this._tableDataset = null;

    var that = this;
    var init = function(){
        // numbered index
        $( '.numbered' ).click( function(){
            that._numberedNavigator.jumpTo( parseInt( $( this ).attr( 'index' ) ) );
        } );
        $( '.backButton' ).click( function(){
            that._numberedNavigator.back();
        } );
        $( '.forwardButton' ).click( function(){
            that._numberedNavigator.forward();
        } );
        $( '.numbered' ).hide();
        $( '.numbered :first').show();
        $( '.numbered :first').addClass( 'currentNumbered' );
        $( '.backButton' ).attr( 'disabled', 'disabled' );
        $( '.forwardButton' ).attr( 'disabled', 'disabled' );
    };
    init();

    var readParamsFromSearchHelper = function(){
        var rows = $( '.helperRow' );
        var queryParams = "";
        for ( var index = 0; index < rows.length; ++index ){
            var eachRow = $( rows[ index ] );

            var children = eachRow.children( 'input' );
            if ( children.length > 0 && $( children[ 0 ] ).val().length > 0 ){
                var firstChild = $( children[ 0 ] );
                var param = eachRow.attr( 'param' );

                if ( children.length == 1 && firstChild.is( '.textInput' ) ){
                    // text input
                    if ( queryParams.length > 0 ){
                        queryParams += "&";
                    }
                    queryParams += ( param + "=" + firstChild.val() );
                } else if ( children.length == 2 && firstChild.is( '.numberInput' ) ){
                    // range input
                    var secondChild = $( children[ 1 ] );
                    if ( secondChild.is( '.numberInput' ) ){
                        if ( queryParams.length > 0 ){
                            queryParams += "&";
                        }
                        queryParams += ( param + "=" + firstChild.val() + "," + secondChild.val() );
                    }
                } else{ continue; }
            }
        }
        return queryParams;
    }

    var clearTable = function(){
        var tableRowElement = $( '.dataRows' );
        tableRowElement.empty();
    }

    var that = this;
    var baseURL = null;
    var onQueryFinished = function( data, jqXHR ){
        var jsonObj = data;
        if ( jsonObj["result"] === "success"){
            $('.sctl').val("");

            gShared_actionManager.clear();
            var pageNum = jsonObj["pageNumber"];
            that._tableDataset = new QueryTableDataset( QueryTable.ajaxQuery, jqXHR.requestURL, pageNum, jsonObj["data"] );
            that._numberedNavigator.setDataset( that._tableDataset );
        }
    }

    this.loadTable = function(){
        clearTable();

        var queryURI = "QueryContestants.php?";
        var params = readParamsFromSearchHelper();

        if ( params.length == 0 ){
            var searchKeyword = $( '.search' ).val();
            if ( searchKeyword.length > 0 ){
                queryURI += "keyword=" + searchKeyword + "&r=" + (~~(Math.random() * 100000)).toString( 16 );
                baseURL = queryURI;

                queryURI = encodeURI( queryURI );
                QueryTable.ajaxQuery( queryURI, onQueryFinished, null );
            }
        } else{
            queryURI += params + "&r=" + (~~(Math.random() * 100000)).toString( 16 );
            baseURL = queryURI;
            queryURI = encodeURI( queryURI );
            QueryTable.ajaxQuery( queryURI, onQueryFinished, null );
        }
    }
}

QueryTable.ajaxQuery = function( url, finishCallback, progressCallback ){

    $.ajax({
        type: "GET",
        url : url,
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        timeout: 1000 * 120,
        beforeSend: function( jqXHR ){
            jqXHR.requestURL = url;

            $( '.contestantsTable' ).fadeOut( 500, function(){
                this.style.width = "100%";
            } );
            $( '.numberedNavigator' ).fadeOut( 500 );
            $( '.bottomActionBox').fadeOut( 200 );

            $( '.containerMask').fadeIn( 1000, function(){
                spinner.spin( $( '.containerMask' )[ 0 ] );
            } );
        },
        success: function( data, code, jqXHR ){
            finishCallback( data, jqXHR );
        },
        complete : function( data ){
            $( '.containerMask').fadeOut( 400, function(){
                $('.contestantsTable' ).fadeIn( 500, function(){
                    // avoid border being missing
                    this.style.width = "98%";
                } );
                $( '.numberedNavigator' ).fadeIn( 500 );
                $( '.bottomActionBox').fadeIn( 200 );
                spinner.stop();
            } );
        }
    });
}

QueryTable.prototype.getDataByRowIndex = function( rowIndex ){
    var contestant = null;
    if ( this._tableDataset != null ){
        var currentPageDataset = this._tableDataset._dataset[ this._tableDataset._currentNumber ];
        if ( currentPageDataset != null && currentPageDataset.length >= rowIndex ){
            contestant = currentPageDataset[ rowIndex ];
        }
    }
    return contestant;
}

QueryTable.prototype.updateDataByIndex = function( rowIndex, contesant ){
    if ( this._tableDataset != null ){
        this._tableDataset.updateContestantRow( rowIndex, contesant );
    }
}

QueryTable.prototype.appendExistedRow = function( $row, contestant ){
    if ( $row != null && contestant != null ){
        var dataTable = $( '.dataRows' );
        if ( dataTable.length > 0 ){
            $row.appendTo( dataTable );

            this._tableDataset._createdTables[ this._tableDataset._currentNumber ].push( $row[ 0 ] );
            this._tableDataset._dataset[ this._tableDataset._currentNumber ].push( contestant );

            $row.find( 'input[type="checkbox"]').click( onSelected );
            $row.find( '.inlineEdit' ).click( onInlineRowEdit );
            $row.find( '.inlineDelete' ).click( onInlineRowDelete );

            // update row info
            $row.find( '.contestantID').html( contestant._id );
            $row.find( '.contestantName').html( contestant._name );
            $row.find( '.contestantGrade').html( contestant._grade );
            $row.find( '.contestantSchool').html( contestant._school );
            $row.find( '.contestantScore').html( contestant._score );
        }
    }
}

QueryTable.prototype.deleteRowByIndex = function( rowIndex ){
    if ( this._tableDataset != null ){
        var currentPageDataset = this._tableDataset._dataset[ this._tableDataset._currentNumber ];
        var currentTableRow = this._tableDataset._createdTables[ this._tableDataset._currentNumber ];
        if ( currentPageDataset != null && currentTableRow != null && currentPageDataset.length >= rowIndex ){
            currentPageDataset.splice( rowIndex, 1 );
            currentTableRow.splice( rowIndex, 1 );
        }
    }
}
// search action
var isSearching = false;
function onSearch(){

    if ( isSearching ){
        return;
    }

    $( '.headTabInner[ref="3"]').click();
    gShared_table.loadTable();
}

/* Paper template #B */
var PaperTemplateSet = function(){
    this._paperTemplates = {};
}

PaperTemplateSet.loadPaperTemplate = function( id ){
    var paperTemplate = null;
    if ( id != null ){
        var reqUrl = "QueryPaper.php?pid=" + id.toString();
        $.ajax({
            type: "GET",
            url: reqUrl,
            async: false,
            timeout: 1000 * 60,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function( data ){
                if ( data["result"] == "success" ){
                    paperTemplate = data["data"];
                    // parse each
                    var quesTemplate = paperTemplate[ "quesTemplate" ];
                    var quesIndexes = quesTemplate[ "index" ];
                    for ( var eachIndex = 0; eachIndex < quesIndexes.length; ++eachIndex ){
                        var eachQuesTemplate = quesTemplate[ quesIndexes[ eachIndex ] ];

                        /// @determinate the type by label
                        //
                        var labels = eachQuesTemplate[ "labels" ].split( ',' );
                        var isScoreAsValue = ( labels[ 1 ].indexOf( "分值" ) != -1 );

                        eachQuesTemplate[ "type" ] = isScoreAsValue ? "Score" : "TrueOrFalse";

                        if ( !isScoreAsValue ){
                            // ### ? maybe x.5 point?
                            var pointPerQues =  ( ~~( eachQuesTemplate["score"] / eachQuesTemplate[ "count" ] ) );
                            eachQuesTemplate[ "point" ] = pointPerQues;
                        }
                    }
                }
            }
        });
    }
    return paperTemplate;
}

PaperTemplateSet.prototype.getPaperTemplateByID = function( paperID ){
    if ( paperID in this._paperTemplates ){
        return this._paperTemplates[ paperID ];
    } else{
        var paperTemplate = PaperTemplateSet.loadPaperTemplate( paperID );
        if ( paperTemplate != null ){
            this._paperTemplates[ paperID ] = paperTemplate;
        }
        return paperTemplate;
    }
}

/* Paper template #B */

/*  ModifiedEntry  #B */
var ModifiedEntry = function( contestant ){
    this._modifiedContestant = Contestant.Clone( contestant );
    this._contestant = contestant;
    this._paper = PaperTemplateSet.loadPaperTemplate( contestant._paperID );
    this._quesTemplate = this._paper["quesTemplate"];
    this._quesTypeModified = {};
}

var ModificationDataException = function(){
    return {
        message : "修改成绩数据出错，请取消该修改项，重新再试。",
        level : "Critical"
    };
}

ModifiedEntry.prototype.modifyCorrectAndWrong = function( index, num, original, brandNew, isTurnToWrong ){
    if ( original !== brandNew ){
        if ( !( index in this._quesTypeModified ) ){
            this._quesTypeModified[ index ] = {};
        }
        var quesType = this._quesTypeModified[ index ];

        //  @JSON data will be revised for adding each question point
        var point = !isTurnToWrong ? this._quesTemplate[ index ][ "point" ] : -( this._quesTemplate[ index ][ "point" ] );
        this._modifiedContestant._score += point;

        if ( num in quesType ){
            if ( quesType[ num ].isTurnToWrong != isTurnToWrong ){
                var offset = quesType[ num ].offset + point;
                if ( offset == 0 ){
                    delete quesType[ num ];
                } else{
                    quesType[ num ].from = original;
                    quesType[ num ].to = brandNew;
                    quesType[ num ].isTurnToWrong = isTurnToWrong;
                    quesType[ num ].offset = offset;
                }
            } else{
                // not modified
                throw new ModificationDataException();
            }
        } else{
            quesType[ num ] = { offset: point, from: original, to: brandNew, isPointModified : false, isTurnToWrong: isTurnToWrong };
        }
    }
}

ModifiedEntry.prototype.modifyPoint = function( index, num, original, brandNew ){
    if ( original !== brandNew ){
        if ( !( index in this._quesTypeModified ) ){
            this._quesTypeModified[ index ] = {};
        }
        var quesType = this._quesTypeModified[ index ];
        var offsetScore = ( brandNew - original );

        if ( num in quesType ){
            // check the last edit
            var lastEdit = quesType[ num ];

            if ( original == lastEdit.to ){
                // return to the origin
                if ( brandNew == lastEdit.from ){
                    delete quesType[ num ];
                } else{
                    quesType[ num ].to = brandNew;
                    quesType[ num ].isTurnToWrong = ( brandNew == 0 ? true : false );
                    quesType[ num ].offset += offsetScore;
                }
            } else{
                // do not modify
                throw new ModificationDataException();
            }
        } else{
            // create new
            quesType[ num ] = { offset: offsetScore, from: original, to: brandNew, isPointModified : true, isTurnToWrong: ( brandNew == 0 ? true : false ) };
        }
        this._modifiedContestant._score += offsetScore;
    }
}

// paper can not be null
//
ModifiedEntry.prototype.updateContestant = function(){
    if ( this._quesTypeModified == null
        || this._paper == null
        || this._quesTypeModified.length == 0 ){
        return;
    }

    for ( var eachIndex in this._quesTypeModified ){
        var domains = this._quesTemplate[eachIndex]["domains"].split( ',' );
        var details = this._modifiedContestant._detail;
        var scorePrintArray = details[eachIndex]["print"];
        var eachIndexModified = this._quesTypeModified[ eachIndex ];

        for ( var quesNum in eachIndexModified ){
            var value = eachIndexModified[ quesNum ];

            var currentQuesDomain = domains[ parseInt( quesNum ) ];

            // update score
            scorePrintArray[ quesNum ] = value.to.toString();
            details[eachIndex][ "score" ] += value.offset;

            // point modification
            // update domain count
            var currentDomain = details[ currentQuesDomain ];
            if ( value.isPointModified ){
                // add
                if ( value.from == 0 && !value.isTurnToWrong ){
                    currentDomain["count"]++;
                    currentDomain["print"]++;
                } else if ( value.to == 0 && value.isTurnToWrong ){
                    // minus
                    currentDomain["count"]--;
                    currentDomain["print"]--;
                }
            } else{
                if ( value.isTurnToWrong ){
                    currentDomain["count"]--;
                    currentDomain["print"]--;
                } else{
                    currentDomain["count"]++;
                    currentDomain["print"]++;
                }
            }

            details[eachIndex]["print"] = scorePrintArray;
        }
    }

}
/*  ModifiedEntry  #E */

/*  Validator   #B  */
var Validator = function(){
    Validator.prototype.isValid = function( str ){}
}

function TrueFalseValidator( TrueStr, FalseStr ){
    this._True = TrueStr.toUpperCase();
    this._False = FalseStr.toUpperCase();
}

TrueFalseValidator.prototype = new Validator();
TrueFalseValidator.prototype.constructor = TrueFalseValidator;
TrueFalseValidator.prototype.isValid = function( value ){
    return value.toUpperCase() == this._True || value.toUpperCase() == this._False;
}

function ScoreValidator( minScore, maxScore ){
    this.minScore = minScore;
    this.maxScore = maxScore;
}
ScoreValidator.prototype = new Validator();
ScoreValidator.prototype.constructor = ScoreValidator;
ScoreValidator.prototype.isValid = function( value ){
    return value >= this.minScore && value <= this.maxScore;
}

var gShared_trueFalseValidator = new TrueFalseValidator( "T", "F" );
/*  Validator  #E */

/*  Contestant Edit #B  */
var ContestantEdit = function(){
    this._scoreEditCache = {};
    this._currentEditRow = null;
    this._currentEditContestant = null;
    this._currentModifiedEntry = null;

    var init = function(){
        $( '.editOK' ).click( onSubmitRowEdit );
        $( '.editCancel' ).click( onCancelRowEdit );

        $( '.nameEdit input').change( onNameChanged );
        $( '.schoolEdit input').change( onSchoolChanged );
    }

    init();
    this.popEditBox = function( $row ){
        if ( $row.length != 1 ){
            return false;
        }

        var contestant = gShared_table.getDataByRowIndex( parseInt( $row.index() ) );
        if ( contestant != null && contestant._paperID != null ){
            var scoreEdit = null;
            if ( contestant._paperID in this._scoreEditCache ){
                scoreEdit = this._scoreEditCache[ contestant._paperID ];
                var paper = gShared_paperTemplateSet.getPaperTemplateByID( contestant._paperID );
                this.fillAllData($row, contestant, scoreEdit);
                this.fillDataWith( paper, scoreEdit, contestant );
                return true;
            } else{
                // generate the score edit UI
                var paper = gShared_paperTemplateSet.getPaperTemplateByID( contestant._paperID );
                if ( paper != null ){
                    try{
                        scoreEdit = ContestantEdit.generateScoreEditTable( paper, contestant );
                        // fill data
                    } catch( exce ){
                        if ( exce.level == "Fatal" ){
                            alert( exce.message );
                        } else{
                            scoreEdit = null;
                        }
                    }

                    if ( scoreEdit != null ){
                        this._scoreEditCache[ contestant._paperID ] = scoreEdit;
                    }
                }
            }

            if ( scoreEdit != null ){
                this.fillAllData($row, contestant, scoreEdit);
                return true;
            }
        }
        return false;
    }
    this.fillAllData = function($row, contestant, scoreEdit){
        // fill the inputs
        var $editBox = $( '.editBox' );
        $editBox.find( '.nameEdit input:first' ).val( contestant._name );
        $editBox.find( '.schoolEdit input:first' ).val( contestant._school );
        $editBox.find( '.scoreEdit span:first' ).html( contestant._score );

        var $editScoreWrapper = $( '.editScoreWrapper' );
        $editScoreWrapper.find( '.editDetailTableWrapper' ).remove();

        this._currentEditRow = $row;
        this._currentEditContestant = contestant;

        var cacheEntry = gShared_actionManager.getAction( contestant._id );
        this._currentModifiedEntry = cacheEntry != null ? cacheEntry._modifiedEntry : new ModifiedEntry( contestant );

        $( scoreEdit ).appendTo( $editScoreWrapper );

        // register callback
        $( '.trueOrFalseInput').change( onTrueFalseValueChanged );
        $( '.scoreInput').change( onScoreValueChanged );
    }

    this.fillDataWith = function( paper, scoreEdit, contestant ){
        if ( paper != null && scoreEdit != null && contestant != null && this._currentModifiedEntry != null){
            var quesTemplate = paper[ "quesTemplate" ];
            var indexes = quesTemplate[ "index" ];
            var contestantScoreDetail = contestant._detail;
            var modified = this._currentModifiedEntry._quesTypeModified;
            for ( var indexAt = 0; indexAt < indexes.length; ++indexAt ){
                var eachIndex = indexes[ indexAt ];
                var detail = quesTemplate[ eachIndex ];

                var indexModified = ( eachIndex in modified ) ? modified[ eachIndex ] : null;

                var eachContestantDetail = contestantScoreDetail[ eachIndex ];

                var range = ContestantEdit.getRange( detail );
                var start = range.start;
                var range = range.end - start;

                var values = eachContestantDetail[ "print" ];

                var $eachTable = $( '.editDetailTable[index="' + eachIndex +  '"]');

                if ( $eachTable.length != 0 ){
                    var datatype = $eachTable.attr( 'datatype' );

                    var $rows = $eachTable.find( '.editInputRow' );
                    // find been modified or not

                    if ( indexModified == null ){
                        if ( datatype == 'TrueOrFalse' ){
                            for ( var index = 0; index < $rows.length; ++index ){
                                var $eachRow = $( $rows[ index ] );
                                var $inputs = $eachRow.find( 'input' );
                                for ( var at = 0; at < range; ++at ){
                                    var value = values[ at ];
                                    $( $inputs[ at ]).attr( 'previous', value );
                                    $( $inputs[ at ] ).val( value );
                                }
                            }
                        } else if ( datatype == 'Score'){
                            for ( var index = 0; index < $rows.length; ++index ){
                                var $eachRow = $( $rows[ index ] );
                                var $inputs = $eachRow.find( 'input' );
                                for ( var at = 0; at < range; ++at ){
                                    var value = values[ at ];
                                    $( $inputs[ at ]).attr( 'previous', value );
                                    $( $inputs[ at ] ).val( value );
                                }
                            }
                        }
                    } else{
                        if ( datatype == 'TrueOrFalse' ){
                            for ( var index = 0; index < $rows.length; ++index ){
                                var $eachRow = $( $rows[ index ] );
                                var $inputs = $eachRow.find( 'input' );
                                for ( var at = 0; at < range; ++at ){
                                    // in the modification
                                    var value = values[ at ];
                                    if ( at in indexModified ){
                                        value = indexModified[ at ].to;
                                    }

                                    $( $inputs[ at ]).attr( 'previous', value );
                                    $( $inputs[ at ] ).val( value );
                                }
                            }
                        } else if ( datatype == 'Score'){
                            for ( var index = 0; index < $rows.length; ++index ){
                                var $eachRow = $( $rows[ index ] );
                                var $inputs = $eachRow.find( 'input' );
                                for ( var at = 0; at < range; ++at ){
                                    var value = values[ at ];
                                    if ( at in indexModified ){
                                        value = indexModified[ at ].to;
                                    }
                                    $( $inputs[ at ]).attr( 'previous', value );
                                    $( $inputs[ at ] ).val( value );
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}

var DataCorruptedException = function(){
    return {
        message : "修改成绩数据出错，请取消该修改项，重新再试。",
        level : "Fatal"
    };
}

ContestantEdit.getRange = function( questionTypeDetail ){
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

ContestantEdit.generateScoreEditTable = function( paper, contestant ){
    var quesTemplate = paper[ "quesTemplate" ];
    var indexes = quesTemplate[ "index" ];
    var $wrapper = $( "<div class='editDetailTableWrapper'></div>" );
    var contestantScoreDetail = contestant._detail;
    for ( var indexAt = 0; indexAt < indexes.length; ++indexAt ){

        var eachIndex = indexes[ indexAt ];
        var detail = quesTemplate[ eachIndex ];
        var type = detail[ "type" ];
        var eachContestantDetail = contestantScoreDetail[ eachIndex ];
        var $detailTable = $( "<table class='editDetailTable' datatype='"+ type + "' + index='" + eachIndex + "'></table>" );
        // first
        var firstRow = "<tr><th style='padding-right: 10px; padding-top: 10px' rowspan='2'>" + eachIndex + "</th>";

        var values = eachContestantDetail[ "print" ];

        var secondRow = "<tr class='editInputRow'>";

        var range = ContestantEdit.getRange( detail );
        var start = range.start;
        var range = range.end - start;

        if ( type == "TrueOrFalse" ){
            for ( var at = 0; at < range; ++at ){
                firstRow += "<th>" + ( start + at ) + "</th>";
                secondRow += "<td><input class='trueOrFalseInput' type='text' value='" + values[ at ] + "' previous='" +
                    values[ at ] + "'></td>";
            }
        } else if ( type == "Score" ){
            var scores = detail[ "values" ].split( ',' );
            if ( scores.length == range ){
                for ( var at = 0; at < range; ++at ){
                    firstRow += "<th>" + ( start + at ) + "</th>";
                    secondRow += "<td><input class='scoreInput' type='text' value='" + values[ at ] + "' min='0' max='" +
                        scores[ at] + "' previous='" + values[ at ] + "'></td>";
                }
            } else{
                throw new DataCorruptedException();
            }
        } else{
            throw new DataCorruptedException();
        }

        $( firstRow ).appendTo( $detailTable );
        $( secondRow ).appendTo( $detailTable );

        $( $detailTable ).appendTo( $wrapper );
    }
    return $wrapper;
}

ContestantEdit.prototype.doRowEdit = function( row ){
    if ( this.popEditBox( row ) != false ){
        $( '.actionBox').hide();
        $( '.containerMask' ).css('background', 'rgba(0,0,0,0.1)');
        $( '.containerMask' ).fadeIn( 300, function(){
            var $editBox = $( '.editBox' );
            $editBox.css( 'margin-top', -( $( '.editBox' ).height() / 2 ) );
            $editBox.fadeIn( 200 );
        });
    } else{
        alert( "数据损坏，请点击删除，提交后用Excel重新添加该考生数据");
    }
}

ContestantEdit.prototype.doCancelRowEdit = function(){
    this._currentEditRow = null;
    this._currentEditContestant = null;
    this._currentModifiedEntry = null;

    var $editBox = $( '.editBox' );
    $editBox.fadeOut( 200, function(){
        $( '.containerMask').fadeOut( 300 );
    } );
}

ContestantEdit.prototype.doRowDelete = function( $focusRows ){
    if ( $focusRows.length == 0 ){
        return;
    }

    for ( var i = 0; i < $focusRows.length; ++i ){
        var $row = $( $focusRows[ i ] );
        var contestant = gShared_table.getDataByRowIndex( parseInt( $row.index() ) );
        var entry = new DeleteAction( new ModifiedEntry( contestant ), $row );
        gShared_actionManager.addAction( entry );

        gShared_table.deleteRowByIndex( parseInt( $row.index() ) );
        $row.remove();
    }
}

function onNameChanged(){
    var value = $( this ).val();
    var modifiedEntry = gShared_contestantEdit._currentModifiedEntry;
    var name = modifiedEntry._contestant._name;
    if ( value == "" ){
        alert( "名字不能为空" );
        $( this ).val( name );
    } else if ( value.length > 29 ){
        alert( "名字太长" );
        $( this ).val( name );
    } else{
        modifiedEntry._modifiedContestant._name = value;
    }
}

function onSchoolChanged(){
    var value = $( this ).val();
    var modifiedEntry = gShared_contestantEdit._currentModifiedEntry;
    var school = modifiedEntry._contestant._school;
    if ( value == "" ){
        alert( "学校不能为空" );
        $( this ).val( school );
    } else if ( value.length > 99 ){
        alert( "学校名字太长" );
        $( this ).val( school );
    } else if ( value == "全省" ){
        alert( "学校名不能为全省" );
        $( this ).val( school );
    } else{
        modifiedEntry._modifiedContestant._school = value;
    }
}

function onTrueFalseValueChanged(){
    var value = $( this ).val().toUpperCase();
    var previous = $( this ).attr( "previous" ).toUpperCase();

    if ( gShared_trueFalseValidator.isValid( value ) ){
        var $td = $( this).closest( 'td' );
        var $table = $td.closest( '.editDetailTable');
        var index = $table.attr( 'index' );

        var modifiedEntry = gShared_contestantEdit._currentModifiedEntry;
        modifiedEntry.modifyCorrectAndWrong( index, $td.index()
            ,previous, value,  value == "F" );

        // update score
        $( this ).attr( "previous", value );
        $( '.scoreEdit span:first').html( modifiedEntry._modifiedContestant._score );
    } else{
        $( this).val( $( this ).attr("previous") );
    }
}

function onScoreValueChanged(){
    var value = parseInt( $( this ).val() );

    var validator = new ScoreValidator( parseInt( $( this ).attr( 'min' ) ), parseInt( $( this ).attr( 'max' ) ) );
    if ( validator.isValid( value ) ){
        var $td = $( this).closest( 'td' );
        var index = $td.closest( '.editDetailTable').attr( 'index' );

        var modifiedEntry = gShared_contestantEdit._currentModifiedEntry;
        // the first one is table title
        modifiedEntry.modifyPoint( index, $td.index(), parseInt( $( this ).attr("previous") ), value );

        // update score
        $( this ).attr( "previous", value );
        $( '.scoreEdit span:first').html( modifiedEntry._modifiedContestant._score );
    } else{
        $( this).val( $( this ).attr("previous") );
    }
}

function onInlineRowEdit(){
    var $row = $( this).closest( 'tr' );
    if ( !( $row.is( '.rowSelected') ) ){
        $row.find( 'input[type="checkbox"]').click();
    }
    gShared_contestantEdit.doRowEdit( $row );
}

function onInlineRowDelete(){
    var $row = $( this).closest( 'tr' );
    gShared_contestantEdit.doRowDelete( $row );
}

function onRowEdit(){
    // find the checked check box
    var $selectedRow = $( '.dataRows' ).find( '.rowSelected:first' );
    gShared_contestantEdit.doRowEdit( $selectedRow );
}

function onRowDelete(){
    var $selectedRows = $( '.dataRows' ).find( '.rowSelected' );
    gShared_contestantEdit.doRowDelete( $selectedRows );
}

function onSubmitRowEdit(){
    var index = parseInt( gShared_contestantEdit._currentEditRow.index() );
    var action = new EditAction( gShared_contestantEdit._currentModifiedEntry, index );
    gShared_actionManager.addAction( action );
    // update data
    gShared_table.updateDataByIndex( index
        , gShared_contestantEdit._currentModifiedEntry._modifiedContestant );

    gShared_contestantEdit._currentEditRow = null;
    gShared_contestantEdit._currentEditContestant = null;
    gShared_contestantEdit._currentModifiedEntry = null;

    var $editBox = $( '.editBox' );
    $editBox.fadeOut( 200, function(){
        $( '.containerMask').fadeOut( 300 );
    } );
}

function onCancelRowEdit(){
    gShared_contestantEdit.doCancelRowEdit();
}

function onSelectAllRow(){
    var $rows = $( '.dataRows' ).children( 'tr' );
    var selectCount = 0;
    for ( var i = 0; i < $rows.length; ++i ){
        var $eachRow = $( $rows[ i ] );
        if ( !( $eachRow.is( '.rowSelected') ) ){
            $eachRow.find( 'input[type="checkbox"]').click();
        } else{
            ++selectCount;
        }
    }

    // undo select all
    if ( selectCount != 0 && selectCount == $rows.length ){
        for ( var i = 0; i < $rows.length; ++i ){
            $( $rows[ i ] ).find( 'input[type="checkbox"]').click();
        }
    }
}
/*  Contestant Edit #E  */

/*  Action  #B  */
function onDialogExit(){
    $( this).closest( '.commonDialog').fadeOut( 300, function(){
        $( '.containerMask' ).fadeOut( 200 );
    });
}

function initPanelAction(){
    var $panel = $( '.panelActions' );
    $( '.actionBoxEntry div:first').click( onPopOutActionBox );
    $( '.dialogExit' ).click( onDialogExit );

    $panel.find( '.remove').click( onRowDelete );
    $panel.find( '.edit').click( onRowEdit );
    $panel.find( '.selectAll').click( onSelectAllRow );

    $( '.submitModifications').click( onSubmitModifications );
    $( '.removeModifications' ).click( onCancelModifications );

    $( '.actionSelectReverse').click( onSelectReverseAction );
    $( '.actionSelectAll').click( onSelectAllAction );
}

var onPopOutActionBox = function(){
    $( '.containerMask' ).css('background', 'rgba(0,0,0,0.1)');
    $( '.editBox').hide();
    $( '.containerMask' ).fadeIn( 300, function(){
        $( '.actionBox' ).fadeIn( 200 );
    });
}

var Action = function(){}
Action.prototype.toTableRow = function(){}
Action.prototype.undo = function(){}

function EditAction( modifiedEntry, index ){
    this._modifiedEntry = modifiedEntry;
    this._rowIndex = index;
}
EditAction.prototype = new Action();
EditAction.prototype.constructor = EditAction;
EditAction.prototype.generateModifier = function(){
    var contestant = this._modifiedEntry._contestant;
    var modifiedContestant = this._modifiedEntry._modifiedContestant;

    var modifiedQuesType = this._modifiedEntry._quesTypeModified;
    var actionString = "";
    for ( var eachIndex in modifiedQuesType ){
        var eachIndexModified = modifiedQuesType[ eachIndex ];
        for ( var eachModified in eachIndexModified ){
            var value = eachIndexModified[ eachModified ];
            var quesNumber = parseInt( eachModified ) + 1;
            actionString += (eachIndex + "题号：" + quesNumber + "，值由" + value.from + "改至" + value.to + "<br>");
        }
    }

    // name & school
    if ( contestant._name != modifiedContestant._name ){
        actionString += "姓名被修改<br>";
    }
    if ( contestant._school != modifiedContestant._school ){
        actionString += "学校名称被修改<br>";
    }
    return actionString;
}

EditAction.prototype.updateTableRow = function( modifier, $row ){
    var modifiedContestant = this._modifiedEntry._modifiedContestant;

    $row.children( '.contestantIDCell').html( modifiedContestant._id );
    $row.children( '.actionTypeCell').html( "修改" );
    $row.children( '.actionDetailCell').html( modifier );
}

EditAction.prototype.undo = function(){
    gShared_table.updateDataByIndex( this._rowIndex, this._modifiedEntry._contestant );
}

function DeleteAction( modifiedEntry, $deletedRow ){
    this._modifiedEntry = modifiedEntry;
    this._$deleteRow = $deletedRow;
}
DeleteAction.prototype = new Action();
DeleteAction.prototype.constructor = DeleteAction;
DeleteAction.prototype.updateTableRow = function( modifier, $row ){
    var modifiedContestant = this._modifiedEntry._modifiedContestant;
    $row.children( '.contestantIDCell').html( modifiedContestant._id );
    $row.children( '.actionTypeCell').html( "删除" );
    $row.children( '.actionDetailCell').html( modifier );
}

// undo delete
DeleteAction.prototype.undo = function(){
    if ( this._modifiedEntry != null && this._$deleteRow != null ){
        gShared_table.appendExistedRow( this._$deleteRow, this._modifiedEntry._contestant );
    }
}

var ActionManager = function(){
    this._actions = {};
    this.createTableRow = function(){
        return $( "<tr><td><input type='checkbox'></td><td class='contestantIDCell'></td><td class='actionTypeCell'></td><td class='actionDetailCell'></td></tr>" );
    }
}

ActionManager.prototype.clear = function(){
    this._actions = {};
    // clear bound table
    $( '.actionTableData' ).empty();
    $( '.actionSelectAll').prop( 'checked', false );
}

ActionManager.prototype.getAction = function( id ){
    if ( id in this._actions ){
        return this._actions[ id ].actionInstance;
    }
    return null;
}

ActionManager.prototype.makeSubmitData = function(){
    var jsonDataArray = [];
    for ( var eachId in this._actions ){
        var action = this._actions[ eachId ].actionInstance;
        if ( action != null ){
            var modifEntry = action._modifiedEntry;
            var eachJSONObject = {};

            var contestant = modifEntry._modifiedContestant;
            if ( action instanceof EditAction ){
                eachJSONObject[ "action" ] = "update";

                modifEntry.updateContestant();

                eachJSONObject[ "applyID" ] = contestant._id;
                eachJSONObject[ "name" ] = contestant._name;
                eachJSONObject[ "school" ] = contestant._school;
                eachJSONObject[ "detail" ] = contestant._detail;
                eachJSONObject[ "score" ] = contestant._score;
                eachJSONObject[ "paperID" ] = contestant._paperID;

            } else if ( action instanceof DeleteAction ){
                eachJSONObject[ "action" ] = "delete";
                eachJSONObject[ "applyID" ] = contestant._id;
                eachJSONObject[ "paperID" ] = contestant._paperID;
            } else{
                continue;
            }

            jsonDataArray.push( eachJSONObject );
        }
    }
    return JSON.stringify( jsonDataArray );
}

ActionManager.prototype.addAction = function( action ){
    if ( action != null ){
        var id = action._modifiedEntry._modifiedContestant._id;

        var isEdit = action instanceof EditAction;
        var modifier = isEdit ? action.generateModifier() : "删除";
        if ( id in this._actions ){
            if ( modifier == ""){
                // remove
                this.removeAction( id );
            } else{
                action.updateTableRow( modifier, this._actions[ id ].boundRow );
                this._actions[ id ].actionInstance = action;
            }
        } else{
            if ( modifier != ""){
                var $row = this.createTableRow();
                action.updateTableRow( modifier, $row );
                this._actions[ id ] = { actionInstance: action, boundRow: $row };
                $row.appendTo ( $( '.actionTableData' ) );

                // events
                $row.find( 'input[type="checkbox"]').click( onSelected );
            }
        }
    }
}

ActionManager.prototype.removeAction = function( id ){
    if ( id in this._actions ){
        var action = this._actions[ id ];
        if ( action != null ){
            var instance = action.actionInstance;
            instance.undo();
            action.boundRow.remove();
            delete this._actions[ id ];
        }
    }
}

ActionManager.prototype.submitAction = function( id ){
    if ( id in this._actions ){
        var action = this._actions[ id ];

        if ( action != null ){
            var instance = action.actionInstance;
            action.boundRow.remove();
            delete this._actions[ id ];
        }
    }
}

var hasSubmitted = false;
function onSubmitModifications(){
    if ( hasSubmitted ){
        return;
    }

    var $selected = $( '.actionTableData').children( '.rowSelected' );
    if ( $selected.length == 0 ){
        alert( "请选择至少一个修改项");
        return;
    }

    var jsonDataInString = gShared_actionManager.makeSubmitData();

    var url = "./UpdateScores.php";
    $.ajax({
            url:url,
            type: "POST",
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            data: jsonDataInString,
            timeout: 1000 * 120,    // 5mins
            beforeSend: function(){
                hasSubmitted = true;
                $( '.actionBox').fadeOut( 200, function(){
                    var $mask = $( '.containerMask');
                    if ( !$mask.is(':visible') ){
                        $mask.show();
                    }
                    $mask.css('background','rgba(0, 0, 0, 0.65)');
                    spinner.spin( $mask[ 0 ] );
                } );
            },
            success: function( jsonObject ){
                // update result
                if ( jsonObject["result"] == "success" ){
                    var set = jsonObject["set"];
                    if ( $.isArray( set ) ){
                        for ( var each = 0; each < set.length; ++each ){
                            gShared_actionManager.submitAction( set[ each ] );
                        }
                    }
                }
            },
            complete: function(){
                var $mask = $( '.containerMask');
                $mask.css('background','rgba(0, 0, 0, 0.1)');
                $( '.actionBox').fadeIn( 200, function(){
                    spinner.stop();
                });
                hasSubmitted = false;
            }
        }
    );
}

function onCancelModifications(){
    // getting all
    var $selected = $( '.actionTableData').children( '.rowSelected' );
    if ( $selected.length == 0 ){
        alert( "请选择至少一个修改项");
    } else{
        for ( var index = 0; index < $selected.length; ++index ){
            var $eachSelectedRow = $( $selected[ index ] );
            var id = $eachSelectedRow.children( '.contestantIDCell').html();
            gShared_actionManager.removeAction( id );
        }
    }
}

function onSelectAllAction(){
    if ( $( this ).is( ':checked' ) ){
        var $allRows = $( '.actionTableData').children( 'tr' );
        for ( var index = 0; index < $allRows.length; ++index ){
            var $each = $( $allRows[ index ] );
            if ( !( $each.is( '.rowSelected') ) ){
                $each.find( 'input[type="checkbox"]').click();
            }
        }
    }
}

function onSelectReverseAction(){
    var $allRows = $( '.actionTableData').children( 'tr' );
    for ( var index = 0; index < $allRows.length; ++index ){
        var $each = $( $allRows[ index ] );
        $each.find( 'input[type="checkbox"]').click();
    }
}

function onSelected(){
    if ( $( this ).is( ':checked' ) ){
        $( this).parents( 'tr:first').addClass( 'rowSelected' );
    } else{
        $( this).parents( 'tr:first').removeClass( 'rowSelected' );
    }
}

/*  Action  #E  */

/*  export  #B  */
var Exporter = function(){
    this._years = null;
    this._grades = null;

    var init = function(){
        $( '.export').click( Exporter.ShowExportDialog );
        $( '.yearSelect').change( Exporter.SelectYearChanged );
        $( '.exportOK').click( Exporter.DoExport );
        $( '.exportCancel').click( Exporter.CloseDialog );
    };

    init();
}

Exporter.ClearSelector = function( $selector ){
    $selector.find( 'option' ).remove();
}

Exporter.DoExport = function(){
    var grade = $( '.gradeSelect').val();
    var year = $( '.yearSelect').val();

    if ( ( grade == null || grade == "" )
        || ( year == null || year == "" ) ){
        $( '.exportResult').html( "非法的年级或是年份参数,请尝试重新打开对话框" );
        return;
    }

    var url = "./ExportScores.php?grade=" + grade + "&year=" + year;
    $.ajax({
        type: "GET",
        url : url,
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        timeout: 1000 * 300,   // 5 minutes
        beforeSend: function( jqXHR ){
            $( '.exportBox').fadeOut( 500, function(){
                spinner.spin( $( '.containerMask' )[ 0 ] );
            } );
        },
        success: function( data ){  // not export success
            if ( data[ "result" ] == "success" ){
                $( '.containerMask' ).fadeOut( 1000, function(){
                    spinner.stop();
                    window.location.href = "./DownloadExcel.php?fid=" + data["fid"];
                } );
            } else{
                var msg = "错误, 请重新尝试";
                if ( "message" in data ){
                    msg = data[ "message" ];
                }
                $( '.exportBox').fadeIn( 500, function(){
                    spinner.stop();
                    $( '.exportResult').html( msg );
                } );
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            if( textStatus === "timeout" ) {
                $( '.exportBox').fadeIn( 500, function(){
                    $( '.exportResult').html( "已超时, 请尝试重新导出" );
                    spinner.stop();
                } );
            } else{
                $( '.exportBox').fadeIn( 500, function(){
                    $( '.exportResult').html( "错误, 请重新尝试" );
                    spinner.stop();
                } );
            }
        }
    });
}

Exporter.UpdateYearSelector = function( years ){
    // clear first
    var $yearSelector = $( '.yearSelect');
    Exporter.ClearSelector( $yearSelector );

    var innerHTML = "";
    for ( var eachYear in years ){
        innerHTML += "<option>" + years[eachYear] + "</option>";
    }

    if ( innerHTML.length != 0 ){
        $( innerHTML ).appendTo( $yearSelector );
    }
}

Exporter.UpdateGradeSelector = function( grades ){
    var $gradeSelector = $( '.gradeSelect' );
    Exporter.ClearSelector( $gradeSelector );

    var innerHTML = "";
    for ( var eachGrade in grades ){
        innerHTML += "<option>" + grades[eachGrade] + "</option>";
    }

    if ( grades.length > 0 ){
        innerHTML += "<option>全部年级</option>";
    }

    if ( innerHTML.length != 0 ){
        $( innerHTML ).appendTo( $gradeSelector );
    }
}

Exporter.prototype.queryGrade = function( year ){
    if ( year == null && !( $.inArray( year, this._years ) ) ){
        return;
    }

    if ( this._grades == null ){
        this._grades = [];
    }

    if ( year in this._grades ){
        Exporter.UpdateGradeSelector( this._grades[ year ] );
        return;
    }

    var url = "./QueryGrade.php?year=" + year;
    var that = this;
    $.ajax({
        type: "GET",
        url : url,
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        timeout: 3000,
        beforeSend: function(){
            Exporter.ClearSelector( $( '.gradeSelect' ) );
        },
        success: function( data ){
            if ( data["result"] == "success" ){
                var grades = data["grades"];
                Exporter.UpdateGradeSelector( grades );
                that._grades[ year ] = grades;
            }
        }
    });
}

Exporter.prototype.queryYear = function(){
    var url = "./QueryYear.php";
    var that = this;
    $.ajax({
        type: "GET",
        url : url,
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        timeout: 3000,
        beforeSend: function( jqXHR ){
            Exporter.ClearSelector( $( '.yearSelect' ) );
            Exporter.ClearSelector( $( '.gradeSelect' ) );
        },
        success: function( data ){
            if ( data["result"] == "success" ){
                var years = data["years"];
                Exporter.UpdateYearSelector( years );
                that._years = years;

                if ( years.length > 0 ){
                    var top = years[ 0 ];
                    that.queryGrade( top );
                }
            }
        }
    });
}

Exporter.ShowExportDialog = function(){
    var $mask = $( '.containerMask');
    $mask.css('background','rgba(0, 0, 0, 0.1)');
    $mask.fadeIn( 500, function(){
        $( '.exportBox' ).fadeIn();
    } );

    gShared_exporter.queryYear();
}

Exporter.SelectYearChanged = function(){
    var year = $( this ).val();
    gShared_exporter.queryGrade( year );
}

Exporter.CloseDialog = function(){
    Exporter.ClearSelector( $( '.yearSelect' ) );
    Exporter.ClearSelector( $( '.gradeSelect' ) );

    gShared_exporter._years = null;
    gShared_exporter._grades = null;

    $( '.exportResult').html( "" );
    $( '.exportBox' ).fadeOut();

    $( '.containerMask' ).fadeOut( 1000 );
}


/*  export  #E  */
