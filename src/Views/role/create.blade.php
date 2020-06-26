@extends('adminlte::page')
@section('content')
    <style type="text/css">
        .pickListButtons {
          padding: 10px;
          text-align: center;
        }

        .pickListButtons button {
          margin-bottom: 5px;
          width: 40px;
        }

        .pickListSelect {
          height: 200px !important;
        }
        .nopadding{
            padding: 0px;
        }
        .headerSelect{
            color: #797979;
            border-bottom: 1px solid transparent;
            border-color: #DDDDDD;
            padding-top:7px;
            padding-bottom: 10px;
        }
        .packListPadding{
            padding:20px 7px;
            width: 99%;
        }
        form{
            width: 95% ;
        }
    </style>
    <?php
        if(session('porigin')){
            $porigin = session('porigin');
        }
        if(session('pselected')){
            $pselected = session('pselected');
        }
    ?>
    <div class="wraper container-fluid">
        <div class="page-title"> 
            @if($form->getModel())
                <h3 class="title">{{  ('Edit role') }}</h3> 
            @else
                <h3 class="title">{{  ('Create new role') }}</h3> 
            @endif
        </div>
        <div class="panel">                    
            <div class="panel-body">
                <div class="row">
                        {!!form_start($form)!!}
                        {!! form_row($form->name) !!}
                        {!! form_row($form->module) !!}
                        {!! form_row($form->role) !!}
                        {!! form_row($form->permission) !!}
                        <div class="col-lg-12">
                            <div class="row">
                                <label class="control-label col-lg-2">Select Permission </label>
                                <div class="col-lg-7 nopadding">
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-5 ">
                                                <div class="headerSelect">
                                                    <b>{{ ('Available')}}</b>
                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <div class="headerSelect">
                                                    &nbsp;
                                                </div>
                                            </div>
                                            <div class="col-lg-5">
                                                <div class="headerSelect">
                                                    <b>{{ ('Selected')}}</b>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                           <div id="pickList" class="packListPadding"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="height" class="control-label col-lg-2">
                           
                            </label>  
                            <div class="col-lg-7">
                                <button type="submit" class="btn btn-effect-ripple btn-primary" id="submitRole"><i class="fa fa-save"></i> {{  ('Save') }}</button>
                            </div>
                        </div>

                        {!!form_end($form)!!}

                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script type="text/javascript">
        (function ($) {

           $.fn.pickList = function (options) {

              var opts = $.extend({}, $.fn.pickList.defaults, options);

              this.fill = function () {
                 var option = '';

                 $.each(opts.data, function (key, val) {
                    option += '<option id=' + val.id + '>' + val.text + '</option>';
                 });
                 this.find('#pickData').append(option);
              };
              this.controll = function () {
                 var pickThis = this;

                 $("#pAdd").on('click', function (e) {
                    e.preventDefault();
                    var p = pickThis.find("#pickData option:selected");
                    p.clone().appendTo("#pickListResult");
                    p.remove();
                 });

                 $("#pAddAll").on('click', function (e) {
                    e.preventDefault();
                    var p = pickThis.find("#pickData option");
                    p.clone().appendTo("#pickListResult");
                    p.remove();
                 });

                 $("#pRemove").on('click', function (e) {
                    e.preventDefault();
                    var p = pickThis.find("#pickListResult option:selected");
                    p.clone().appendTo("#pickData");
                    p.remove();
                 });

                 $("#pRemoveAll").on('click', function (e) {
                    e.preventDefault();
                    var p = pickThis.find("#pickListResult option");
                    p.clone().appendTo("#pickData");
                    p.remove();
                 });
              };
              this.getValues = function () {
                 var objResult = [];
                 this.find("#pickListResult option").each(function () {
                    objResult.push({id: this.id, text: this.text});
                 });
                 return objResult;
              };
              this.getSelectList = function () {
                 var objResult = [];
                 this.find("#pickListResult option").each(function () {
                    objResult.push(this.text);
                 });
                 return objResult;
              };
              this.getPickDataList = function () {
                 var objResult = [];
                 this.find("#pickData option").each(function () {
                    objResult.push(this.text);
                 });
                 return objResult;
              };
              this.init = function () {
                 var pickListHtml =
                         "<div class='row'>" +
                         "  <div class='col-sm-5'>" +
                         "   <select class='form-control pickListSelect' id='pickData' multiple></select>" +
                         " </div>" +
                         " <div class='col-sm-2 pickListButtons'><div>" +
                         "  <button id='pAdd' class='btn btn-primary btn-sm'>" + opts.add + "</button></div><div>" +
                         "  <button id='pAddAll' class='btn btn-primary btn-sm'>" + opts.addAll + "</button></div><div>" +
                         "  <button id='pRemove' class='btn btn-primary btn-sm'>" + opts.remove + "</button></div><div>" +
                         "  <button id='pRemoveAll' class='btn btn-primary btn-sm'>" + opts.removeAll + "</button></div>" +
                         " </div>" +
                         " <div class='col-sm-5'>" +
                         "    <select class='form-control pickListSelect' id='pickListResult' name='pickListResult' multiple></select>" +
                         " </div>" +
                         "</div>";

                 this.append(pickListHtml);

                 this.fill();
                 this.controll();
              };

              this.init();
              return this;
           };

           $.fn.pickList.defaults = {
              add: '>',
              addAll: '>>',
              remove: '<',
              removeAll: '<<'
           };
        }(jQuery));
    </script>
    <script type="text/javascript">
        pick = $("#pickList").pickList({
              data: ""
            });
        $(document).ready(function(){
            
            //back from valide
            optionOrigin = "";
            optionSelected = "";
            jsonporigin = '{!!json_encode($porigin)!!}';
            porigin = JSON.parse(jsonporigin);
            if(porigin){
                $.each(porigin,function(index,value){
                    optionOrigin+= "<option id="+value+">"+value+"</option>";
                });
            }
            $('#pickData').html('');
            $('#pickData').append(optionOrigin);  

            jsonpselected = '{!!json_encode($pselected)!!}';
            pselected = JSON.parse(jsonpselected);
            if(pselected){
                $.each(pselected,function(index,value){
                    optionSelected+= "<option id="+value+">"+value+"</option>";

                });
                console.log(optionSelected);
            }
            $('#pickListResult').html('');
            $('#pickListResult').append(optionSelected);

            //     

            $('#module').change(function(){
                allRole = JSON.parse('{!! ($jsonRole) !!}'); 
                module = $(this).val();
                roleStr = allRole[module];
                optionRole = "";
                roleLength=0;
                resultArr = [];
                $("#pickListResult option").each(function(index,value){
                    resultArr[this.index] =this.value;
                });

                if(roleStr){
                    roleLength = roleStr.length;
                }
                for (i = roleLength - 1; i >= 0; i--) {
                    existed = $.inArray(roleStr[i],resultArr);
                    if(existed==-1){
                        optionRole += "<option id="+roleStr[i]+">"+roleStr[i]+"</option>";    
                    }
                };
                $('#pickData').html('');
                $('#pickData').append(optionRole);

            });

            $('#submitRole').click(function(e){
                getResult = pick.getSelectList();
                getData = pick.getPickDataList();
                $('input[name="role"]').val(getResult);
                $('input[name="permission"]').val(getData);
            });
        });

        
        // $("#pickListResult").append('<option id=' + "test" + '>' + "Momo" + '</option>');
        $("#getSelected").click(function(e) {
            e.preventDefault();
            console.log(pick.getValues());
        });
    </script>
@stop

