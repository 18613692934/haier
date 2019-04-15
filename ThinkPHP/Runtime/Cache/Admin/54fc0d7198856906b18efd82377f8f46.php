<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
         <script type="text/javascript" src="/Public/static/datatables/1.10.0/jquery.dataTables.min.js"></script> 
          <script type="text/javascript" src="/Public/js/jquery-1.11.1.min.js"></script> 
        <title>TODO supply a title</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
       
    </head>
    <body>
        <table class="table table-striped table-bordered table-hover" id="table_report">
    <thead>
        <tr role="row">
            <th class="table-checkbox">
                选择
            </th>
            <th>图片</th>
            <th>商品名称</th>
            <th>商品编码</th>
            <th>商品类型</th>
            <th>价格</th>
            <th>会员价格</th>
        </tr>
    </thead>
    <tbody></tbody>
    <tfoot>
        <tr role="row">
            <th class="table-checkbox">
                <input type="checkbox" class="group-checkable" data-set="#table_report .checkboxes" />
            </th>
            <th>图片</th>
            <th>商品名称</th>
            <th>商品编码</th>
            <th>商品类型</th>
            <th>价格</th>
            <th>会员价格</th>
        </tr>
    </tfoot>
</table>

    </body>
    
    
</html>