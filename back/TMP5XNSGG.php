<?php

require_once('../Connections/maconnexion.php');
//deconnexion
include('../php/logout.php');

if ($_SESSION['statut'])
{
} else {
// Redirection vers Haut Conseil
header("Status: 301 Moved Permanently", false, 301);
header('Location: ../connexion.php');
exit();
}

$colname_ch_pat_confimation_suppression = "-1";
if (isset($_POST['monument_ID'])) {
  $colname_ch_pat_confimation_suppression = $_POST['monument_ID'];
}
mysql_select_db($database_maconnexion, $maconnexion);
$query_ch_pat_confimation_suppression = sprintf("SELECT ch_pat_id, ch_pat_villeID, ch_pat_nom, ch_pat_lien_img1 FROM patrimoine WHERE ch_pat_id = %s", GetSQLValueString($colname_ch_pat_confimation_suppression, "int"));
$ch_pat_confimation_suppression = mysql_query($query_ch_pat_confimation_suppression, $maconnexion) or die(mysql_error());
$row_ch_pat_confimation_suppression = mysql_fetch_assoc($ch_pat_confimation_suppression);
$totalRows_ch_pat_confimation_suppression = mysql_num_rows($ch_pat_confimation_suppression);

$_SESSION['ville_encours'] = $row_ch_pat_confimation_suppression['ch_pat_villeID'];
?><!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta charset="utf-8">
<title>Supprimer un monument</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<link rel="stylesheet" title="D&eacute;sactiv&eacute; pour l'Aper&ccedil;u dans le navigateur : ../assets/css/bootstrap.css">
<style type="text/css">

/*!
 * Bootstrap v2.1.1
 *
 * Copyright 2012 Twitter, Inc
 * Licensed under the Apache License v2.0
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Designed and built with all the love in the world @twitter by @mdo and @fat.
 */

article,
aside,
details,
figcaption,
figure,
footer,
header,
hgroup,
nav,
section {
  display: block;
}

audio,
canvas,
video {
  display: inline-block;
  *display: inline;
  *zoom: 1;
}

audio:not([controls]) {
  display: none;
}

html {
  font-size: 100%;
  -webkit-text-size-adjust: 100%;
      -ms-text-size-adjust: 100%;
}

a:focus {
  outline: thin dotted #333;
  outline: 5px auto -webkit-focus-ring-color;
  outline-offset: -2px;
}

a:hover,
a:active {
  outline: 0;
}

sub,
sup {
  position: relative;
  font-size: 75%;
  line-height: 0;
  vertical-align: baseline;
}

sup {
  top: -0.5em;
}

sub {
  bottom: -0.25em;
}

img {
  width: auto\9;
  height: auto;
  max-width: 100%;
  vertical-align: middle;
  border: 0;
  -ms-interpolation-mode: bicubic;
}

#map_canvas img {
  max-width: none;
}

button,
input,
select,
textarea {
  margin: 0;
  font-size: 100%;
  vertical-align: middle;
}

button,
input {
  *overflow: visible;
  line-height: normal;
}

button::-moz-focus-inner,
input::-moz-focus-inner {
  padding: 0;
  border: 0;
}

button,
input[type="button"],
input[type="reset"],
input[type="submit"] {
  cursor: pointer;
  -webkit-appearance: button;
}

input[type="search"] {
  -webkit-box-sizing: content-box;
     -moz-box-sizing: content-box;
          box-sizing: content-box;
  -webkit-appearance: textfield;
}

input[type="search"]::-webkit-search-decoration,
input[type="search"]::-webkit-search-cancel-button {
  -webkit-appearance: none;
}

textarea {
  overflow: auto;
  vertical-align: top;
}

.clearfix {
  *zoom: 1;
}

.clearfix:before,
.clearfix:after {
  display: table;
  line-height: 0;
  content: "";
}

.clearfix:after {
  clear: both;
}

.hide-text {
  font: 0/0 a;
  color: transparent;
  text-shadow: none;
  background-color: transparent;
  border: 0;
}

.input-block-level {
  display: block;
  width: 100%;
  min-height: 30px;
  -webkit-box-sizing: border-box;
     -moz-box-sizing: border-box;
          box-sizing: border-box;
}

body {
  margin: 0;
  font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
  font-size: 14px;
  line-height: 20px;
  color: #333333;
  background-color: #ffffff;
}

a {
  color: #0088cc;
  text-decoration: none;
}

a:hover {
  color: #005580;
  text-decoration: underline;
}

.img-rounded {
  -webkit-border-radius: 6px;
     -moz-border-radius: 6px;
          border-radius: 6px;
}

.img-polaroid {
  padding: 4px;
  background-color: #fff;
  border: 1px solid #ccc;
  border: 1px solid rgba(0, 0, 0, 0.2);
  -webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
     -moz-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
          box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.img-circle {
  -webkit-border-radius: 500px;
     -moz-border-radius: 500px;
          border-radius: 500px;
}

.row {
  margin-left: -20px;
  *zoom: 1;
}

.row:before,
.row:after {
  display: table;
  line-height: 0;
  content: "";
}

.row:after {
  clear: both;
}

[class*="span"] {
  float: left;
  min-height: 1px;
  margin-left: 20px;
}

.container,
.navbar-static-top .container,
.navbar-fixed-top .container,
.navbar-fixed-bottom .container {
  width: 940px;
}

.span12 {
  width: 940px;
}

.span11 {
  width: 860px;
}

.span10 {
  width: 780px;
}

.span9 {
  width: 700px;
}

.span8 {
  width: 620px;
}

.span7 {
  width: 540px;
}

.span6 {
  width: 460px;
}

.span5 {
  width: 380px;
}

.span4 {
  width: 300px;
}

.span3 {
  width: 220px;
}

.span2 {
  width: 140px;
}

.span1 {
  width: 60px;
}

.offset12 {
  margin-left: 980px;
}

.offset11 {
  margin-left: 900px;
}

.offset10 {
  margin-left: 820px;
}

.offset9 {
  margin-left: 740px;
}

.offset8 {
  margin-left: 660px;
}

.offset7 {
  margin-left: 580px;
}

.offset6 {
  margin-left: 500px;
}

.offset5 {
  margin-left: 420px;
}

.offset4 {
  margin-left: 340px;
}

.offset3 {
  margin-left: 260px;
}

.offset2 {
  margin-left: 180px;
}

.offset1 {
  margin-left: 100px;
}

.row-fluid {
  width: 100%;
  *zoom: 1;
}

.row-fluid:before,
.row-fluid:after {
  display: table;
  line-height: 0;
  content: "";
}

.row-fluid:after {
  clear: both;
}

.row-fluid [class*="span"] {
  display: block;
  float: left;
  width: 100%;
  min-height: 30px;
  margin-left: 2.127659574468085%;
  *margin-left: 2.074468085106383%;
  -webkit-box-sizing: border-box;
     -moz-box-sizing: border-box;
          box-sizing: border-box;
}

.row-fluid [class*="span"]:first-child {
  margin-left: 0;
}

.row-fluid .span12 {
  width: 100%;
  *width: 99.94680851063829%;
}

.row-fluid .span11 {
  width: 91.48936170212765%;
  *width: 91.43617021276594%;
}

.row-fluid .span10 {
  width: 82.97872340425532%;
  *width: 82.92553191489361%;
}

.row-fluid .span9 {
  width: 74.46808510638297%;
  *width: 74.41489361702126%;
}

.row-fluid .span8 {
  width: 65.95744680851064%;
  *width: 65.90425531914893%;
}

.row-fluid .span7 {
  width: 57.44680851063829%;
  *width: 57.39361702127659%;
}

.row-fluid .span6 {
  width: 48.93617021276595%;
  *width: 48.88297872340425%;
}

.row-fluid .span5 {
  width: 40.42553191489362%;
  *width: 40.37234042553192%;
}

.row-fluid .span4 {
  width: 31.914893617021278%;
  *width: 31.861702127659576%;
}

.row-fluid .span3 {
  width: 23.404255319148934%;
  *width: 23.351063829787233%;
}

.row-fluid .span2 {
  width: 14.893617021276595%;
  *width: 14.840425531914894%;
}

.row-fluid .span1 {
  width: 6.382978723404255%;
  *width: 6.329787234042553%;
}

.row-fluid .offset12 {
  margin-left: 104.25531914893617%;
  *margin-left: 104.14893617021275%;
}

.row-fluid .offset12:first-child {
  margin-left: 102.12765957446808%;
  *margin-left: 102.02127659574467%;
}

.row-fluid .offset11 {
  margin-left: 95.74468085106382%;
  *margin-left: 95.6382978723404%;
}

.row-fluid .offset11:first-child {
  margin-left: 93.61702127659574%;
  *margin-left: 93.51063829787232%;
}

.row-fluid .offset10 {
  margin-left: 87.23404255319149%;
  *margin-left: 87.12765957446807%;
}

.row-fluid .offset10:first-child {
  margin-left: 85.1063829787234%;
  *margin-left: 84.99999999999999%;
}

.row-fluid .offset9 {
  margin-left: 78.72340425531914%;
  *margin-left: 78.61702127659572%;
}

.row-fluid .offset9:first-child {
  margin-left: 76.59574468085106%;
  *margin-left: 76.48936170212764%;
}

.row-fluid .offset8 {
  margin-left: 70.2127659574468%;
  *margin-left: 70.10638297872339%;
}

.row-fluid .offset8:first-child {
  margin-left: 68.08510638297872%;
  *margin-left: 67.9787234042553%;
}

.row-fluid .offset7 {
  margin-left: 61.70212765957446%;
  *margin-left: 61.59574468085106%;
}

.row-fluid .offset7:first-child {
  margin-left: 59.574468085106375%;
  *margin-left: 59.46808510638297%;
}

.row-fluid .offset6 {
  margin-left: 53.191489361702125%;
  *margin-left: 53.085106382978715%;
}

.row-fluid .offset6:first-child {
  margin-left: 51.063829787234035%;
  *margin-left: 50.95744680851063%;
}

.row-fluid .offset5 {
  margin-left: 44.68085106382979%;
  *margin-left: 44.57446808510638%;
}

.row-fluid .offset5:first-child {
  margin-left: 42.5531914893617%;
  *margin-left: 42.4468085106383%;
}

.row-fluid .offset4 {
  margin-left: 36.170212765957444%;
  *margin-left: 36.06382978723405%;
}

.row-fluid .offset4:first-child {
  margin-left: 34.04255319148936%;
  *margin-left: 33.93617021276596%;
}

.row-fluid .offset3 {
  margin-left: 27.659574468085104%;
  *margin-left: 27.5531914893617%;
}

.row-fluid .offset3:first-child {
  margin-left: 25.53191489361702%;
  *margin-left: 25.425531914893618%;
}

.row-fluid .offset2 {
  margin-left: 19.148936170212764%;
  *margin-left: 19.04255319148936%;
}

.row-fluid .offset2:first-child {
  margin-left: 17.02127659574468%;
  *margin-left: 16.914893617021278%;
}

.row-fluid .offset1 {
  margin-left: 10.638297872340425%;
  *margin-left: 10.53191489361702%;
}

.row-fluid .offset1:first-child {
  margin-left: 8.51063829787234%;
  *margin-left: 8.404255319148938%;
}

[class*="span"].hide,
.row-fluid [class*="span"].hide {
  display: none;
}

[class*="span"].pull-right,
.row-fluid [class*="span"].pull-right {
  float: right;
}

.container {
  margin-right: auto;
  margin-left: auto;
  *zoom: 1;
}

.container:before,
.container:after {
  display: table;
  line-height: 0;
  content: "";
}

.container:after {
  clear: both;
}

.container-fluid {
  padding-right: 20px;
  padding-left: 20px;
  *zoom: 1;
}

.container-fluid:before,
.container-fluid:after {
  display: table;
  line-height: 0;
  content: "";
}

.container-fluid:after {
  clear: both;
}

p {
  margin: 0 0 10px;
}

.lead {
  margin-bottom: 20px;
  font-size: 21px;
  font-weight: 200;
  line-height: 30px;
}

small {
  font-size: 85%;
}

strong {
  font-weight: bold;
}

em {
  font-style: italic;
}

cite {
  font-style: normal;
}

.muted {
  color: #999999;
}

.text-warning {
  color: #c09853;
}

.text-error {
  color: #b94a48;
}

.text-info {
  color: #3a87ad;
}

.text-success {
  color: #468847;
}

h1,
h2,
h3,
h4,
h5,
h6 {
  margin: 10px 0;
  font-family: inherit;
  font-weight: bold;
  line-height: 1;
  color: inherit;
  text-rendering: optimizelegibility;
}

h1 small,
h2 small,
h3 small,
h4 small,
h5 small,
h6 small {
  font-weight: normal;
  line-height: 1;
  color: #999999;
}

h1 {
  font-size: 36px;
  line-height: 40px;
}

h2 {
  font-size: 30px;
  line-height: 40px;
}

h3 {
  font-size: 24px;
  line-height: 40px;
}

h4 {
  font-size: 18px;
  line-height: 20px;
}

h5 {
  font-size: 14px;
  line-height: 20px;
}

h6 {
  font-size: 12px;
  line-height: 20px;
}

h1 small {
  font-size: 24px;
}

h2 small {
  font-size: 18px;
}

h3 small {
  font-size: 14px;
}

h4 small {
  font-size: 14px;
}

.page-header {
  padding-bottom: 9px;
  margin: 20px 0 30px;
  border-bottom: 1px solid #eeeeee;
}

ul,
ol {
  padding: 0;
  margin: 0 0 10px 25px;
}

ul ul,
ul ol,
ol ol,
ol ul {
  margin-bottom: 0;
}

li {
  line-height: 20px;
}

ul.unstyled,
ol.unstyled {
  margin-left: 0;
  list-style: none;
}

dl {
  margin-bottom: 20px;
}

dt,
dd {
  line-height: 20px;
}

dt {
  font-weight: bold;
}

dd {
  margin-left: 10px;
}

.dl-horizontal {
  *zoom: 1;
}

.dl-horizontal:before,
.dl-horizontal:after {
  display: table;
  line-height: 0;
  content: "";
}

.dl-horizontal:after {
  clear: both;
}

.dl-horizontal dt {
  float: left;
  width: 160px;
  overflow: hidden;
  clear: left;
  text-align: right;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.dl-horizontal dd {
  margin-left: 180px;
}

hr {
  margin: 20px 0;
  border: 0;
  border-top: 1px solid #eeeeee;
  border-bottom: 1px solid #ffffff;
}

abbr[title] {
  cursor: help;
  border-bottom: 1px dotted #999999;
}

abbr.initialism {
  font-size: 90%;
  text-transform: uppercase;
}

blockquote {
  padding: 0 0 0 15px;
  margin: 0 0 20px;
  border-left: 5px solid #eeeeee;
}

blockquote p {
  margin-bottom: 0;
  font-size: 16px;
  font-weight: 300;
  line-height: 25px;
}

blockquote small {
  display: block;
  line-height: 20px;
  color: #999999;
}

blockquote small:before {
  content: '\2014 \00A0';
}

blockquote.pull-right {
  float: right;
  padding-right: 15px;
  padding-left: 0;
  border-right: 5px solid #eeeeee;
  border-left: 0;
}

blockquote.pull-right p,
blockquote.pull-right small {
  text-align: right;
}

blockquote.pull-right small:before {
  content: '';
}

blockquote.pull-right small:after {
  content: '\00A0 \2014';
}

q:before,
q:after,
blockquote:before,
blockquote:after {
  content: "";
}

address {
  display: block;
  margin-bottom: 20px;
  font-style: normal;
  line-height: 20px;
}

code,
pre {
  padding: 0 3px 2px;
  font-family: Monaco, Menlo, Consolas, "Courier New", monospace;
  font-size: 12px;
  color: #333333;
  -webkit-border-radius: 3px;
     -moz-border-radius: 3px;
          border-radius: 3px;
}

code {
  padding: 2px 4px;
  color: #d14;
  background-color: #f7f7f9;
  border: 1px solid #e1e1e8;
}

pre {
  display: block;
  padding: 9.5px;
  margin: 0 0 10px;
  font-size: 13px;
  line-height: 20px;
  word-break: break-all;
  word-wrap: break-word;
  white-space: pre;
  white-space: pre-wrap;
  background-color: #f5f5f5;
  border: 1px solid #ccc;
  border: 1px solid rgba(0, 0, 0, 0.15);
  -webkit-border-radius: 4px;
     -moz-border-radius: 4px;
          border-radius: 4px;
}

pre.prettyprint {
  margin-bottom: 20px;
}

pre code {
  padding: 0;
  color: inherit;
  background-color: transparent;
  border: 0;
}

.pre-scrollable {
  max-height: 340px;
  overflow-y: scroll;
}

form {
  margin: 0 0 20px;
}

fieldset {
  padding: 0;
  margin: 0;
  border: 0;
}

legend {
  display: block;
  width: 100%;
  padding: 0;
  margin-bottom: 20px;
  font-size: 21px;
  line-height: 40px;
  color: #333333;
  border: 0;
  border-bottom: 1px solid #e5e5e5;
}

legend small {
  font-size: 15px;
  color: #999999;
}

label,
input,
button,
select,
textarea {
  font-size: 14px;
  font-weight: normal;
  line-height: 20px;
}

input,
button,
select,
textarea {
  font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
}

label {
  display: block;
  margin-bottom: 5px;
}

select,
textarea,
input[type="text"],
input[type="password"],
input[type="datetime"],
input[type="datetime-local"],
input[type="date"],
input[type="month"],
input[type="time"],
input[type="week"],
input[type="number"],
input[type="email"],
input[type="url"],
input[type="search"],
input[type="tel"],
input[type="color"],
.uneditable-input {
  display: inline-block;
  height: 20px;
  padding: 4px 6px;
  margin-bottom: 9px;
  font-size: 14px;
  line-height: 20px;
  color: #555555;
  -webkit-border-radius: 3px;
     -moz-border-radius: 3px;
          border-radius: 3px;
}

input,
textarea,
.uneditable-input {
  width: 206px;
}

textarea {
  height: auto;
}

textarea,
input[type="text"],
input[type="password"],
input[type="datetime"],
input[type="datetime-local"],
input[type="date"],
input[type="month"],
input[type="time"],
input[type="week"],
input[type="number"],
input[type="email"],
input[type="url"],
input[type="search"],
input[type="tel"],
input[type="color"],
.uneditable-input {
  background-color: #ffffff;
  border: 1px solid #cccccc;
  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
     -moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
          box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
  -webkit-transition: border linear 0.2s, box-shadow linear 0.2s;
     -moz-transition: border linear 0.2s, box-shadow linear 0.2s;
       -o-transition: border linear 0.2s, box-shadow linear 0.2s;
          transition: border linear 0.2s, box-shadow linear 0.2s;
}

textarea:focus,
input[type="text"]:focus,
input[type="password"]:focus,
input[type="datetime"]:focus,
input[type="datetime-local"]:focus,
input[type="date"]:focus,
input[type="month"]:focus,
input[type="time"]:focus,
input[type="week"]:focus,
input[type="number"]:focus,
input[type="email"]:focus,
input[type="url"]:focus,
input[type="search"]:focus,
input[type="tel"]:focus,
input[type="color"]:focus,
.uneditable-input:focus {
  border-color: rgba(82, 168, 236, 0.8);
  outline: 0;
  outline: thin dotted \9;
  /* IE6-9 */

  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(82, 168, 236, 0.6);
     -moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(82, 168, 236, 0.6);
          box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(82, 168, 236, 0.6);
}

input[type="radio"],
input[type="checkbox"] {
  margin: 4px 0 0;
  margin-top: 1px \9;
  *margin-top: 0;
  line-height: normal;
  cursor: pointer;
}

input[type="file"],
input[type="image"],
input[type="submit"],
input[type="reset"],
input[type="button"],
input[type="radio"],
input[type="checkbox"] {
  width: auto;
}

select,
input[type="file"] {
  height: 30px;
  /* In IE7, the height of the select element cannot be changed by height, only font-size */

  *margin-top: 4px;
  /* For IE7, add top margin to align select with labels */

  line-height: 30px;
}

select {
  width: 220px;
  background-color: #ffffff;
  border: 1px solid #cccccc;
}

select[multiple],
select[size] {
  height: auto;
}

select:focus,
input[type="file"]:focus,
input[type="radio"]:focus,
input[type="checkbox"]:focus {
  outline: thin dotted #333;
  outline: 5px auto -webkit-focus-ring-color;
  outline-offset: -2px;
}

.uneditable-input,
.uneditable-textarea {
  color: #999999;
  cursor: not-allowed;
  background-color: #fcfcfc;
  border-color: #cccccc;
  -webkit-box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.025);
     -moz-box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.025);
          box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.025);
}

.uneditable-input {
  overflow: hidden;
  white-space: nowrap;
}

.uneditable-textarea {
  width: auto;
  height: auto;
}

input:-moz-placeholder,
textarea:-moz-placeholder {
  color: #999999;
}

input:-ms-input-placeholder,
textarea:-ms-input-placeholder {
  color: #999999;
}

input::-webkit-input-placeholder,
textarea::-webkit-input-placeholder {
  color: #999999;
}

.radio,
.checkbox {
  min-height: 18px;
  padding-left: 18px;
}

.radio input[type="radio"],
.checkbox input[type="checkbox"] {
  float: left;
  margin-left: -18px;
}

.controls > .radio:first-child,
.controls > .checkbox:first-child {
  padding-top: 5px;
}

.radio.inline,
.checkbox.inline {
  display: inline-block;
  padding-top: 5px;
  margin-bottom: 0;
  vertical-align: middle;
}

.radio.inline + .radio.inline,
.checkbox.inline + .checkbox.inline {
  margin-left: 10px;
}

.input-mini {
  width: 60px;
}

.input-small {
  width: 90px;
}

.input-medium {
  width: 150px;
}

.input-large {
  width: 210px;
}

.input-xlarge {
  width: 270px;
}

.input-xxlarge {
  width: 530px;
}

input[class*="span"],
select[class*="span"],
textarea[class*="span"],
.uneditable-input[class*="span"],
.row-fluid input[class*="span"],
.row-fluid select[class*="span"],
.row-fluid textarea[class*="span"],
.row-fluid .uneditable-input[class*="span"] {
  float: none;
  margin-left: 0;
}

.input-append input[class*="span"],
.input-append .uneditable-input[class*="span"],
.input-prepend input[class*="span"],
.input-prepend .uneditable-input[class*="span"],
.row-fluid input[class*="span"],
.row-fluid select[class*="span"],
.row-fluid textarea[class*="span"],
.row-fluid .uneditable-input[class*="span"],
.row-fluid .input-prepend [class*="span"],
.row-fluid .input-append [class*="span"] {
  display: inline-block;
}

input,
textarea,
.uneditable-input {
  margin-left: 0;
}

.controls-row [class*="span"] + [class*="span"] {
  margin-left: 20px;
}

input.span12,
textarea.span12,
.uneditable-input.span12 {
  width: 926px;
}

input.span11,
textarea.span11,
.uneditable-input.span11 {
  width: 846px;
}

input.span10,
textarea.span10,
.uneditable-input.span10 {
  width: 766px;
}

input.span9,
textarea.span9,
.uneditable-input.span9 {
  width: 686px;
}

input.span8,
textarea.span8,
.uneditable-input.span8 {
  width: 606px;
}

input.span7,
textarea.span7,
.uneditable-input.span7 {
  width: 526px;
}

input.span6,
textarea.span6,
.uneditable-input.span6 {
  width: 446px;
}

input.span5,
textarea.span5,
.uneditable-input.span5 {
  width: 366px;
}

input.span4,
textarea.span4,
.uneditable-input.span4 {
  width: 286px;
}

input.span3,
textarea.span3,
.uneditable-input.span3 {
  width: 206px;
}

input.span2,
textarea.span2,
.uneditable-input.span2 {
  width: 126px;
}

input.span1,
textarea.span1,
.uneditable-input.span1 {
  width: 46px;
}

.controls-row {
  *zoom: 1;
}

.controls-row:before,
.controls-row:after {
  display: table;
  line-height: 0;
  content: "";
}

.controls-row:after {
  clear: both;
}

.controls-row [class*="span"] {
  float: left;
}

input[disabled],
select[disabled],
textarea[disabled],
input[readonly],
select[readonly],
textarea[readonly] {
  cursor: not-allowed;
  background-color: #eeeeee;
}

input[type="radio"][disabled],
input[type="checkbox"][disabled],
input[type="radio"][readonly],
input[type="checkbox"][readonly] {
  background-color: transparent;
}

.control-group.warning > label,
.control-group.warning .help-block,
.control-group.warning .help-inline {
  color: #c09853;
}

.control-group.warning .checkbox,
.control-group.warning .radio,
.control-group.warning input,
.control-group.warning select,
.control-group.warning textarea {
  color: #c09853;
}

.control-group.warning input,
.control-group.warning select,
.control-group.warning textarea {
  border-color: #c09853;
  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
     -moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
          box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
}

.control-group.warning input:focus,
.control-group.warning select:focus,
.control-group.warning textarea:focus {
  border-color: #a47e3c;
  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 6px #dbc59e;
     -moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 6px #dbc59e;
          box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 6px #dbc59e;
}

.control-group.warning .input-prepend .add-on,
.control-group.warning .input-append .add-on {
  color: #c09853;
  background-color: #fcf8e3;
  border-color: #c09853;
}

.control-group.error > label,
.control-group.error .help-block,
.control-group.error .help-inline {
  color: #b94a48;
}

.control-group.error .checkbox,
.control-group.error .radio,
.control-group.error input,
.control-group.error select,
.control-group.error textarea {
  color: #b94a48;
}

.control-group.error input,
.control-group.error select,
.control-group.error textarea {
  border-color: #b94a48;
  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
     -moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
          box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
}

.control-group.error input:focus,
.control-group.error select:focus,
.control-group.error textarea:focus {
  border-color: #953b39;
  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 6px #d59392;
     -moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 6px #d59392;
          box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 6px #d59392;
}

.control-group.error .input-prepend .add-on,
.control-group.error .input-append .add-on {
  color: #b94a48;
  background-color: #f2dede;
  border-color: #b94a48;
}

.control-group.success > label,
.control-group.success .help-block,
.control-group.success .help-inline {
  color: #468847;
}

.control-group.success .checkbox,
.control-group.success .radio,
.control-group.success input,
.control-group.success select,
.control-group.success textarea {
  color: #468847;
}

.control-group.success input,
.control-group.success select,
.control-group.success textarea {
  border-color: #468847;
  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
     -moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
          box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
}

.control-group.success input:focus,
.control-group.success select:focus,
.control-group.success textarea:focus {
  border-color: #356635;
  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 6px #7aba7b;
     -moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 6px #7aba7b;
          box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 6px #7aba7b;
}

.control-group.success .input-prepend .add-on,
.control-group.success .input-append .add-on {
  color: #468847;
  background-color: #dff0d8;
  border-color: #468847;
}

.control-group.info > label,
.control-group.info .help-block,
.control-group.info .help-inline {
  color: #3a87ad;
}

.control-group.info .checkbox,
.control-group.info .radio,
.control-group.info input,
.control-group.info select,
.control-group.info textarea {
  color: #3a87ad;
}

.control-group.info input,
.control-group.info select,
.control-group.info textarea {
  border-color: #3a87ad;
  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
     -moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
          box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
}

.control-group.info input:focus,
.control-group.info select:focus,
.control-group.info textarea:focus {
  border-color: #2d6987;
  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 6px #7ab5d3;
     -moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 6px #7ab5d3;
          box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 6px #7ab5d3;
}

.control-group.info .input-prepend .add-on,
.control-group.info .input-append .add-on {
  color: #3a87ad;
  background-color: #d9edf7;
  border-color: #3a87ad;
}

input:focus:required:invalid,
textarea:focus:required:invalid,
select:focus:required:invalid {
  color: #b94a48;
  border-color: #ee5f5b;
}

input:focus:required:invalid:focus,
textarea:focus:required:invalid:focus,
select:focus:required:invalid:focus {
  border-color: #e9322d;
  -webkit-box-shadow: 0 0 6px #f8b9b7;
     -moz-box-shadow: 0 0 6px #f8b9b7;
          box-shadow: 0 0 6px #f8b9b7;
}

.form-actions {
  padding: 19px 20px 20px;
  margin-top: 20px;
  margin-bottom: 20px;
  background-color: #f5f5f5;
  border-top: 1px solid #e5e5e5;
  *zoom: 1;
}

.form-actions:before,
.form-actions:after {
  display: table;
  line-height: 0;
  content: "";
}

.form-actions:after {
  clear: both;
}

.help-block,
.help-inline {
  color: #595959;
}

.help-block {
  display: block;
  margin-bottom: 10px;
}

.help-inline {
  display: inline-block;
  *display: inline;
  padding-left: 5px;
  vertical-align: middle;
  *zoom: 1;
}

.input-append,
.input-prepend {
  margin-bottom: 5px;
  font-size: 0;
  white-space: nowrap;
}

.input-append input,
.input-prepend input,
.input-append select,
.input-prepend select,
.input-append .uneditable-input,
.input-prepend .uneditable-input {
  position: relative;
  margin-bottom: 0;
  *margin-left: 0;
  font-size: 14px;
  vertical-align: top;
  -webkit-border-radius: 0 3px 3px 0;
     -moz-border-radius: 0 3px 3px 0;
          border-radius: 0 3px 3px 0;
}

.input-append input:focus,
.input-prepend input:focus,
.input-append select:focus,
.input-prepend select:focus,
.input-append .uneditable-input:focus,
.input-prepend .uneditable-input:focus {
  z-index: 2;
}

.input-append .add-on,
.input-prepend .add-on {
  display: inline-block;
  width: auto;
  height: 20px;
  min-width: 16px;
  padding: 4px 5px;
  font-size: 14px;
  font-weight: normal;
  line-height: 20px;
  text-align: center;
  text-shadow: 0 1px 0 #ffffff;
  background-color: #eeeeee;
  border: 1px solid #ccc;
}

.input-append .add-on,
.input-prepend .add-on,
.input-append .btn,
.input-prepend .btn {
  vertical-align: top;
  -webkit-border-radius: 0;
     -moz-border-radius: 0;
          border-radius: 0;
}

.input-append .active,
.input-prepend .active {
  background-color: #a9dba9;
  border-color: #46a546;
}

.input-prepend .add-on,
.input-prepend .btn {
  margin-right: -1px;
}

.input-prepend .add-on:first-child,
.input-prepend .btn:first-child {
  -webkit-border-radius: 3px 0 0 3px;
     -moz-border-radius: 3px 0 0 3px;
          border-radius: 3px 0 0 3px;
}

.input-append input,
.input-append select,
.input-append .uneditable-input {
  -webkit-border-radius: 3px 0 0 3px;
     -moz-border-radius: 3px 0 0 3px;
          border-radius: 3px 0 0 3px;
}

.input-append .add-on,
.input-append .btn {
  margin-left: -1px;
}

.input-append .add-on:last-child,
.input-append .btn:last-child {
  -webkit-border-radius: 0 3px 3px 0;
     -moz-border-radius: 0 3px 3px 0;
          border-radius: 0 3px 3px 0;
}

.input-prepend.input-append input,
.input-prepend.input-append select,
.input-prepend.input-append .uneditable-input {
  -webkit-border-radius: 0;
     -moz-border-radius: 0;
          border-radius: 0;
}

.input-prepend.input-append .add-on:first-child,
.input-prepend.input-append .btn:first-child {
  margin-right: -1px;
  -webkit-border-radius: 3px 0 0 3px;
     -moz-border-radius: 3px 0 0 3px;
          border-radius: 3px 0 0 3px;
}

.input-prepend.input-append .add-on:last-child,
.input-prepend.input-append .btn:last-child {
  margin-left: -1px;
  -webkit-border-radius: 0 3px 3px 0;
     -moz-border-radius: 0 3px 3px 0;
          border-radius: 0 3px 3px 0;
}

input.search-query {
  padding-right: 14px;
  padding-right: 4px \9;
  padding-left: 14px;
  padding-left: 4px \9;
  /* IE7-8 doesn't have border-radius, so don't indent the padding */

  margin-bottom: 0;
  -webkit-border-radius: 15px;
     -moz-border-radius: 15px;
          border-radius: 15px;
}

/* Allow for input prepend/append in search forms */

.form-search .input-append .search-query,
.form-search .input-prepend .search-query {
  -webkit-border-radius: 0;
     -moz-border-radius: 0;
          border-radius: 0;
}

.form-search .input-append .search-query {
  -webkit-border-radius: 14px 0 0 14px;
     -moz-border-radius: 14px 0 0 14px;
          border-radius: 14px 0 0 14px;
}

.form-search .input-append .btn {
  -webkit-border-radius: 0 14px 14px 0;
     -moz-border-radius: 0 14px 14px 0;
          border-radius: 0 14px 14px 0;
}

.form-search .input-prepend .search-query {
  -webkit-border-radius: 0 14px 14px 0;
     -moz-border-radius: 0 14px 14px 0;
          border-radius: 0 14px 14px 0;
}

.form-search .input-prepend .btn {
  -webkit-border-radius: 14px 0 0 14px;
     -moz-border-radius: 14px 0 0 14px;
          border-radius: 14px 0 0 14px;
}

.form-search input,
.form-inline input,
.form-horizontal input,
.form-search textarea,
.form-inline textarea,
.form-horizontal textarea,
.form-search select,
.form-inline select,
.form-horizontal select,
.form-search .help-inline,
.form-inline .help-inline,
.form-horizontal .help-inline,
.form-search .uneditable-input,
.form-inline .uneditable-input,
.form-horizontal .uneditable-input,
.form-search .input-prepend,
.form-inline .input-prepend,
.form-horizontal .input-prepend,
.form-search .input-append,
.form-inline .input-append,
.form-horizontal .input-append {
  display: inline-block;
  *display: inline;
  margin-bottom: 0;
  vertical-align: middle;
  *zoom: 1;
}

.form-search .hide,
.form-inline .hide,
.form-horizontal .hide {
  display: none;
}

.form-search label,
.form-inline label,
.form-search .btn-group,
.form-inline .btn-group {
  display: inline-block;
}

.form-search .input-append,
.form-inline .input-append,
.form-search .input-prepend,
.form-inline .input-prepend {
  margin-bottom: 0;
}

.form-search .radio,
.form-search .checkbox,
.form-inline .radio,
.form-inline .checkbox {
  padding-left: 0;
  margin-bottom: 0;
  vertical-align: middle;
}

.form-search .radio input[type="radio"],
.form-search .checkbox input[type="checkbox"],
.form-inline .radio input[type="radio"],
.form-inline .checkbox input[type="checkbox"] {
  float: left;
  margin-right: 3px;
  margin-left: 0;
}

.control-group {
  margin-bottom: 10px;
}

legend + .control-group {
  margin-top: 20px;
  -webkit-margin-top-collapse: separate;
}

.form-horizontal .control-group {
  margin-bottom: 20px;
  *zoom: 1;
}

.form-horizontal .control-group:before,
.form-horizontal .control-group:after {
  display: table;
  line-height: 0;
  content: "";
}

.form-horizontal .control-group:after {
  clear: both;
}

.form-horizontal .control-label {
  float: left;
  width: 160px;
  padding-top: 5px;
  text-align: right;
}

.form-horizontal .controls {
  *display: inline-block;
  *padding-left: 20px;
  margin-left: 180px;
  *margin-left: 0;
}

.form-horizontal .controls:first-child {
  *padding-left: 180px;
}

.form-horizontal .help-block {
  margin-bottom: 0;
}

.form-horizontal input + .help-block,
.form-horizontal select + .help-block,
.form-horizontal textarea + .help-block {
  margin-top: 10px;
}

.form-horizontal .form-actions {
  padding-left: 180px;
}

table {
  max-width: 100%;
  background-color: transparent;
  border-collapse: collapse;
  border-spacing: 0;
}

.table {
  width: 100%;
  margin-bottom: 20px;
}

.table th,
.table td {
  padding: 8px;
  line-height: 20px;
  text-align: left;
  vertical-align: top;
  border-top: 1px solid #dddddd;
}

.table th {
  font-weight: bold;
}

.table thead th {
  vertical-align: bottom;
}

.table caption + thead tr:first-child th,
.table caption + thead tr:first-child td,
.table colgroup + thead tr:first-child th,
.table colgroup + thead tr:first-child td,
.table thead:first-child tr:first-child th,
.table thead:first-child tr:first-child td {
  border-top: 0;
}

.table tbody + tbody {
  border-top: 2px solid #dddddd;
}

.table-condensed th,
.table-condensed td {
  padding: 4px 5px;
}

.table-bordered {
  border: 1px solid #dddddd;
  border-collapse: separate;
  *border-collapse: collapse;
  border-left: 0;
  -webkit-border-radius: 4px;
     -moz-border-radius: 4px;
          border-radius: 4px;
}

.table-bordered th,
.table-bordered td {
  border-left: 1px solid #dddddd;
}

.table-bordered caption + thead tr:first-child th,
.table-bordered caption + tbody tr:first-child th,
.table-bordered caption + tbody tr:first-child td,
.table-bordered colgroup + thead tr:first-child th,
.table-bordered colgroup + tbody tr:first-child th,
.table-bordered colgroup + tbody tr:first-child td,
.table-bordered thead:first-child tr:first-child th,
.table-bordered tbody:first-child tr:first-child th,
.table-bordered tbody:first-child tr:first-child td {
  border-top: 0;
}

.table-bordered thead:first-child tr:first-child th:first-child,
.table-bordered tbody:first-child tr:first-child td:first-child {
  -webkit-border-top-left-radius: 4px;
          border-top-left-radius: 4px;
  -moz-border-radius-topleft: 4px;
}

.table-bordered thead:first-child tr:first-child th:last-child,
.table-bordered tbody:first-child tr:first-child td:last-child {
  -webkit-border-top-right-radius: 4px;
          border-top-right-radius: 4px;
  -moz-border-radius-topright: 4px;
}

.table-bordered thead:last-child tr:last-child th:first-child,
.table-bordered tbody:last-child tr:last-child td:first-child,
.table-bordered tfoot:last-child tr:last-child td:first-child {
  -webkit-border-radius: 0 0 0 4px;
     -moz-border-radius: 0 0 0 4px;
          border-radius: 0 0 0 4px;
  -webkit-border-bottom-left-radius: 4px;
          border-bottom-left-radius: 4px;
  -moz-border-radius-bottomleft: 4px;
}

.table-bordered thead:last-child tr:last-child th:last-child,
.table-bordered tbody:last-child tr:last-child td:last-child,
.table-bordered tfoot:last-child tr:last-child td:last-child {
  -webkit-border-bottom-right-radius: 4px;
          border-bottom-right-radius: 4px;
  -moz-border-radius-bottomright: 4px;
}

.table-bordered caption + thead tr:first-child th:first-child,
.table-bordered caption + tbody tr:first-child td:first-child,
.table-bordered colgroup + thead tr:first-child th:first-child,
.table-bordered colgroup + tbody tr:first-child td:first-child {
  -webkit-border-top-left-radius: 4px;
          border-top-left-radius: 4px;
  -moz-border-radius-topleft: 4px;
}

.table-bordered caption + thead tr:first-child th:last-child,
.table-bordered caption + tbody tr:first-child td:last-child,
.table-bordered colgroup + thead tr:first-child th:last-child,
.table-bordered colgroup + tbody tr:first-child td:last-child {
  -webkit-border-top-right-radius: 4px;
          border-top-right-radius: 4px;
  -moz-border-radius-topleft: 4px;
}

.table-striped tbody tr:nth-child(odd) td,
.table-striped tbody tr:nth-child(odd) th {
  background-color: #f9f9f9;
}

.table-hover tbody tr:hover td,
.table-hover tbody tr:hover th {
  background-color: #f5f5f5;
}

table [class*=span],
.row-fluid table [class*=span] {
  display: table-cell;
  float: none;
  margin-left: 0;
}

.table .span1 {
  float: none;
  width: 44px;
  margin-left: 0;
}

.table .span2 {
  float: none;
  width: 124px;
  margin-left: 0;
}

.table .span3 {
  float: none;
  width: 204px;
  margin-left: 0;
}

.table .span4 {
  float: none;
  width: 284px;
  margin-left: 0;
}

.table .span5 {
  float: none;
  width: 364px;
  margin-left: 0;
}

.table .span6 {
  float: none;
  width: 444px;
  margin-left: 0;
}

.table .span7 {
  float: none;
  width: 524px;
  margin-left: 0;
}

.table .span8 {
  float: none;
  width: 604px;
  margin-left: 0;
}

.table .span9 {
  float: none;
  width: 684px;
  margin-left: 0;
}

.table .span10 {
  float: none;
  width: 764px;
  margin-left: 0;
}

.table .span11 {
  float: none;
  width: 844px;
  margin-left: 0;
}

.table .span12 {
  float: none;
  width: 924px;
  margin-left: 0;
}

.table .span13 {
  float: none;
  width: 1004px;
  margin-left: 0;
}

.table .span14 {
  float: none;
  width: 1084px;
  margin-left: 0;
}

.table .span15 {
  float: none;
  width: 1164px;
  margin-left: 0;
}

.table .span16 {
  float: none;
  width: 1244px;
  margin-left: 0;
}

.table .span17 {
  float: none;
  width: 1324px;
  margin-left: 0;
}

.table .span18 {
  float: none;
  width: 1404px;
  margin-left: 0;
}

.table .span19 {
  float: none;
  width: 1484px;
  margin-left: 0;
}

.table .span20 {
  float: none;
  width: 1564px;
  margin-left: 0;
}

.table .span21 {
  float: none;
  width: 1644px;
  margin-left: 0;
}

.table .span22 {
  float: none;
  width: 1724px;
  margin-left: 0;
}

.table .span23 {
  float: none;
  width: 1804px;
  margin-left: 0;
}

.table .span24 {
  float: none;
  width: 1884px;
  margin-left: 0;
}

.table tbody tr.success td {
  background-color: #dff0d8;
}

.table tbody tr.error td {
  background-color: #f2dede;
}

.table tbody tr.warning td {
  background-color: #fcf8e3;
}

.table tbody tr.info td {
  background-color: #d9edf7;
}

.table-hover tbody tr.success:hover td {
  background-color: #d0e9c6;
}

.table-hover tbody tr.error:hover td {
  background-color: #ebcccc;
}

.table-hover tbody tr.warning:hover td {
  background-color: #faf2cc;
}

.table-hover tbody tr.info:hover td {
  background-color: #c4e3f3;
}

[class^="icon-"],
[class*=" icon-"] {
  display: inline-block;
  width: 14px;
  height: 14px;
  margin-top: 1px;
  *margin-right: .3em;
  line-height: 14px;
  vertical-align: text-top;
  background-image: url("http://www.generation-city.com/monde/assets/img/glyphicons-halflings.png");
  background-position: 14px 14px;
  background-repeat: no-repeat;
}

/* White icons with optional class, or on hover/active states of certain elements */

.icon-white,
.nav-tabs > .active > a > [class^="icon-"],
.nav-tabs > .active > a > [class*=" icon-"],
.nav-pills > .active > a > [class^="icon-"],
.nav-pills > .active > a > [class*=" icon-"],
.nav-list > .active > a > [class^="icon-"],
.nav-list > .active > a > [class*=" icon-"],
.navbar-inverse .nav > .active > a > [class^="icon-"],
.navbar-inverse .nav > .active > a > [class*=" icon-"],
.dropdown-menu > li > a:hover > [class^="icon-"],
.dropdown-menu > li > a:hover > [class*=" icon-"],
.dropdown-menu > .active > a > [class^="icon-"],
.dropdown-menu > .active > a > [class*=" icon-"] {
  background-image: url("http://www.generation-city.com/monde/assets/img/glyphicons-halflings-white.png");
}

.icon-glass {
  background-position: 0      0;
}

.icon-music {
  background-position: -24px 0;
}

.icon-search {
  background-position: -48px 0;
}

.icon-envelope {
  background-position: -72px 0;
}

.icon-heart {
  background-position: -96px 0;
}

.icon-star {
  background-position: -120px 0;
}

.icon-star-empty {
  background-position: -144px 0;
}

.icon-user {
  background-position: -168px 0;
}

.icon-film {
  background-position: -192px 0;
}

.icon-th-large {
  background-position: -216px 0;
}

.icon-th {
  background-position: -240px 0;
}

.icon-th-list {
  background-position: -264px 0;
}

.icon-ok {
  background-position: -288px 0;
}

.icon-remove {
  background-position: -312px 0;
}

.icon-zoom-in {
  background-position: -336px 0;
}

.icon-zoom-out {
  background-position: -360px 0;
}

.icon-off {
  background-position: -384px 0;
}

.icon-signal {
  background-position: -408px 0;
}

.icon-cog {
  background-position: -432px 0;
}

.icon-trash {
  background-position: -456px 0;
}

.icon-home {
  background-position: 0 -24px;
}

.icon-file {
  background-position: -24px -24px;
}

.icon-time {
  background-position: -48px -24px;
}

.icon-road {
  background-position: -72px -24px;
}

.icon-download-alt {
  background-position: -96px -24px;
}

.icon-download {
  background-position: -120px -24px;
}

.icon-upload {
  background-position: -144px -24px;
}

.icon-inbox {
  background-position: -168px -24px;
}

.icon-play-circle {
  background-position: -192px -24px;
}

.icon-repeat {
  background-position: -216px -24px;
}

.icon-refresh {
  background-position: -240px -24px;
}

.icon-list-alt {
  background-position: -264px -24px;
}

.icon-lock {
  background-position: -287px -24px;
}

.icon-flag {
  background-position: -312px -24px;
}

.icon-headphones {
  background-position: -336px -24px;
}

.icon-volume-off {
  background-position: -360px -24px;
}

.icon-volume-down {
  background-position: -384px -24px;
}

.icon-volume-up {
  background-position: -408px -24px;
}

.icon-qrcode {
  background-position: -432px -24px;
}

.icon-barcode {
  background-position: -456px -24px;
}

.icon-tag {
  background-position: 0 -48px;
}

.icon-tags {
  background-position: -25px -48px;
}

.icon-book {
  background-position: -48px -48px;
}

.icon-bookmark {
  background-position: -72px -48px;
}

.icon-print {
  background-position: -96px -48px;
}

.icon-camera {
  background-position: -120px -48px;
}

.icon-font {
  background-position: -144px -48px;
}

.icon-bold {
  background-position: -167px -48px;
}

.icon-italic {
  background-position: -192px -48px;
}

.icon-text-height {
  background-position: -216px -48px;
}

.icon-text-width {
  background-position: -240px -48px;
}

.icon-align-left {
  background-position: -264px -48px;
}

.icon-align-center {
  background-position: -288px -48px;
}

.icon-align-right {
  background-position: -312px -48px;
}

.icon-align-justify {
  background-position: -336px -48px;
}

.icon-list {
  background-position: -360px -48px;
}

.icon-indent-left {
  background-position: -384px -48px;
}

.icon-indent-right {
  background-position: -408px -48px;
}

.icon-facetime-video {
  background-position: -432px -48px;
}

.icon-picture {
  background-position: -456px -48px;
}

.icon-pencil {
  background-position: 0 -72px;
}

.icon-map-marker {
  background-position: -24px -72px;
}

.icon-adjust {
  background-position: -48px -72px;
}

.icon-tint {
  background-position: -72px -72px;
}

.icon-edit {
  background-position: -96px -72px;
}

.icon-share {
  background-position: -120px -72px;
}

.icon-check {
  background-position: -144px -72px;
}

.icon-move {
  background-position: -168px -72px;
}

.icon-step-backward {
  background-position: -192px -72px;
}

.icon-fast-backward {
  background-position: -216px -72px;
}

.icon-backward {
  background-position: -240px -72px;
}

.icon-play {
  background-position: -264px -72px;
}

.icon-pause {
  background-position: -288px -72px;
}

.icon-stop {
  background-position: -312px -72px;
}

.icon-forward {
  background-position: -336px -72px;
}

.icon-fast-forward {
  background-position: -360px -72px;
}

.icon-step-forward {
  background-position: -384px -72px;
}

.icon-eject {
  background-position: -408px -72px;
}

.icon-chevron-left {
  background-position: -432px -72px;
}

.icon-chevron-right {
  background-position: -456px -72px;
}

.icon-plus-sign {
  background-position: 0 -96px;
}

.icon-minus-sign {
  background-position: -24px -96px;
}

.icon-remove-sign {
  background-position: -48px -96px;
}

.icon-ok-sign {
  background-position: -72px -96px;
}

.icon-question-sign {
  background-position: -96px -96px;
}

.icon-info-sign {
  background-position: -120px -96px;
}

.icon-screenshot {
  background-position: -144px -96px;
}

.icon-remove-circle {
  background-position: -168px -96px;
}

.icon-ok-circle {
  background-position: -192px -96px;
}

.icon-ban-circle {
  background-position: -216px -96px;
}

.icon-arrow-left {
  background-position: -240px -96px;
}

.icon-arrow-right {
  background-position: -264px -96px;
}

.icon-arrow-up {
  background-position: -289px -96px;
}

.icon-arrow-down {
  background-position: -312px -96px;
}

.icon-share-alt {
  background-position: -336px -96px;
}

.icon-resize-full {
  background-position: -360px -96px;
}

.icon-resize-small {
  background-position: -384px -96px;
}

.icon-plus {
  background-position: -408px -96px;
}

.icon-minus {
  background-position: -433px -96px;
}

.icon-asterisk {
  background-position: -456px -96px;
}

.icon-exclamation-sign {
  background-position: 0 -120px;
}

.icon-gift {
  background-position: -24px -120px;
}

.icon-leaf {
  background-position: -48px -120px;
}

.icon-fire {
  background-position: -72px -120px;
}

.icon-eye-open {
  background-position: -96px -120px;
}

.icon-eye-close {
  background-position: -120px -120px;
}

.icon-warning-sign {
  background-position: -144px -120px;
}

.icon-plane {
  background-position: -168px -120px;
}

.icon-calendar {
  background-position: -192px -120px;
}

.icon-random {
  width: 16px;
  background-position: -216px -120px;
}

.icon-comment {
  background-position: -240px -120px;
}

.icon-magnet {
  background-position: -264px -120px;
}

.icon-chevron-up {
  background-position: -288px -120px;
}

.icon-chevron-down {
  background-position: -313px -119px;
}

.icon-retweet {
  background-position: -336px -120px;
}

.icon-shopping-cart {
  background-position: -360px -120px;
}

.icon-folder-close {
  background-position: -384px -120px;
}

.icon-folder-open {
  width: 16px;
  background-position: -408px -120px;
}

.icon-resize-vertical {
  background-position: -432px -119px;
}

.icon-resize-horizontal {
  background-position: -456px -118px;
}

.icon-hdd {
  background-position: 0 -144px;
}

.icon-bullhorn {
  background-position: -24px -144px;
}

.icon-bell {
  background-position: -48px -144px;
}

.icon-certificate {
  background-position: -72px -144px;
}

.icon-thumbs-up {
  background-position: -96px -144px;
}

.icon-thumbs-down {
  background-position: -120px -144px;
}

.icon-hand-right {
  background-position: -144px -144px;
}

.icon-hand-left {
  background-position: -168px -144px;
}

.icon-hand-up {
  background-position: -192px -144px;
}

.icon-hand-down {
  background-position: -216px -144px;
}

.icon-circle-arrow-right {
  background-position: -240px -144px;
}

.icon-circle-arrow-left {
  background-position: -264px -144px;
}

.icon-circle-arrow-up {
  background-position: -288px -144px;
}

.icon-circle-arrow-down {
  background-position: -312px -144px;
}

.icon-globe {
  background-position: -336px -144px;
}

.icon-wrench {
  background-position: -360px -144px;
}

.icon-tasks {
  background-position: -384px -144px;
}

.icon-filter {
  background-position: -408px -144px;
}

.icon-briefcase {
  background-position: -432px -144px;
}

.icon-fullscreen {
  background-position: -456px -144px;
}

.dropup,
.dropdown {
  position: relative;
}

.dropdown-toggle {
  *margin-bottom: -3px;
}

.dropdown-toggle:active,
.open .dropdown-toggle {
  outline: 0;
}

.caret {
  display: inline-block;
  width: 0;
  height: 0;
  vertical-align: top;
  border-top: 4px solid #000000;
  border-right: 4px solid transparent;
  border-left: 4px solid transparent;
  content: "";
}

.dropdown .caret {
  margin-top: 8px;
  margin-left: 2px;
}

.dropdown-menu {
  position: absolute;
  top: 100%;
  left: 0;
  z-index: 1000;
  display: none;
  float: left;
  min-width: 160px;
  padding: 5px 0;
  margin: 2px 0 0;
  list-style: none;
  background-color: #ffffff;
  border: 1px solid #ccc;
  border: 1px solid rgba(0, 0, 0, 0.2);
  *border-right-width: 2px;
  *border-bottom-width: 2px;
  -webkit-border-radius: 6px;
     -moz-border-radius: 6px;
          border-radius: 6px;
  -webkit-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
     -moz-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
          box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
  -webkit-background-clip: padding-box;
     -moz-background-clip: padding;
          background-clip: padding-box;
}

.dropdown-menu.pull-right {
  right: 0;
  left: auto;
}

.dropdown-menu .divider {
  *width: 100%;
  height: 1px;
  margin: 9px 1px;
  *margin: -5px 0 5px;
  overflow: hidden;
  background-color: #e5e5e5;
  border-bottom: 1px solid #ffffff;
}

.dropdown-menu a {
  display: block;
  padding: 3px 20px;
  clear: both;
  font-weight: normal;
  line-height: 20px;
  color: #333333;
  white-space: nowrap;
}

.dropdown-menu li > a:hover,
.dropdown-menu li > a:focus,
.dropdown-submenu:hover > a {
  color: #ffffff;
  text-decoration: none;
  background-color: #0088cc;
  background-color: #0081c2;
  background-image: -moz-linear-gradient(top, #0088cc, #0077b3);
  background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#0088cc), to(#0077b3));
  background-image: -webkit-linear-gradient(top, #0088cc, #0077b3);
  background-image: -o-linear-gradient(top, #0088cc, #0077b3);
  background-image: linear-gradient(to bottom, #0088cc, #0077b3);
  background-repeat: repeat-x;
  filter: progid:dximagetransform.microsoft.gradient(startColorstr='#ff0088cc', endColorstr='#ff0077b3', GradientType=0);
}

.dropdown-menu .active > a,
.dropdown-menu .active > a:hover {
  color: #ffffff;
  text-decoration: none;
  background-color: #0088cc;
  background-color: #0081c2;
  background-image: linear-gradient(to bottom, #0088cc, #0077b3);
  background-image: -moz-linear-gradient(top, #0088cc, #0077b3);
  background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#0088cc), to(#0077b3));
  background-image: -webkit-linear-gradient(top, #0088cc, #0077b3);
  background-image: -o-linear-gradient(top, #0088cc, #0077b3);
  background-repeat: repeat-x;
  outline: 0;
  filter: progid:dximagetransform.microsoft.gradient(startColorstr='#ff0088cc', endColorstr='#ff0077b3', GradientType=0);
}

.dropdown-menu .disabled > a,
.dropdown-menu .disabled > a:hover {
  color: #999999;
}

.dropdown-menu .disabled > a:hover {
  text-decoration: none;
  cursor: default;
  background-color: transparent;
}

.open {
  *z-index: 1000;
}

.open > .dropdown-menu {
  display: block;
}

.pull-right > .dropdown-menu {
  right: 0;
  left: auto;
}

.dropup .caret,
.navbar-fixed-bottom .dropdown .caret {
  border-top: 0;
  border-bottom: 4px solid #000000;
  content: "";
}

.dropup .dropdown-menu,
.navbar-fixed-bottom .dropdown .dropdown-menu {
  top: auto;
  bottom: 100%;
  margin-bottom: 1px;
}

.dropdown-submenu {
  position: relative;
}

.dropdown-submenu > .dropdown-menu {
  top: 0;
  left: 100%;
  margin-top: -6px;
  margin-left: -1px;
  -webkit-border-radius: 0 6px 6px 6px;
     -moz-border-radius: 0 6px 6px 6px;
          border-radius: 0 6px 6px 6px;
}

.dropdown-submenu:hover > .dropdown-menu {
  display: block;
}

.dropdown-submenu > a:after {
  display: block;
  float: right;
  width: 0;
  height: 0;
  margin-top: 5px;
  margin-right: -10px;
  border-color: transparent;
  border-left-color: #cccccc;
  border-style: solid;
  border-width: 5px 0 5px 5px;
  content: " ";
}

.dropdown-submenu:hover > a:after {
  border-left-color: #ffffff;
}

.dropdown .dropdown-menu .nav-header {
  padding-right: 20px;
  padding-left: 20px;
}

.typeahead {
  margin-top: 2px;
  -webkit-border-radius: 4px;
     -moz-border-radius: 4px;
          border-radius: 4px;
}

.well {
  min-height: 20px;
  padding: 19px;
  margin-bottom: 20px;
  background-color: #f5f5f5;
  border: 1px solid #e3e3e3;
  -webkit-border-radius: 4px;
     -moz-border-radius: 4px;
          border-radius: 4px;
  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05);
     -moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05);
          box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05);
}

.well blockquote {
  border-color: #ddd;
  border-color: rgba(0, 0, 0, 0.15);
}

.well-large {
  padding: 24px;
  -webkit-border-radius: 6px;
     -moz-border-radius: 6px;
          border-radius: 6px;
}

.well-small {
  padding: 9px;
  -webkit-border-radius: 3px;
     -moz-border-radius: 3px;
          border-radius: 3px;
}

.fade {
  opacity: 0;
  -webkit-transition: opacity 0.15s linear;
     -moz-transition: opacity 0.15s linear;
       -o-transition: opacity 0.15s linear;
          transition: opacity 0.15s linear;
}

.fade.in {
  opacity: 1;
}

.collapse {
  position: relative;
  height: 0;
  overflow: hidden;
  -webkit-transition: height 0.35s ease;
     -moz-transition: height 0.35s ease;
       -o-transition: height 0.35s ease;
          transition: height 0.35s ease;
}

.collapse.in {
  height: auto;
}

.close {
  float: right;
  font-size: 20px;
  font-weight: bold;
  line-height: 20px;
  color: #000000;
  text-shadow: 0 1px 0 #ffffff;
  opacity: 0.2;
  filter: alpha(opacity=20);
}

.close:hover {
  color: #000000;
  text-decoration: none;
  cursor: pointer;
  opacity: 0.4;
  filter: alpha(opacity=40);
}

button.close {
  padding: 0;
  cursor: pointer;
  background: transparent;
  border: 0;
  -webkit-appearance: none;
}

.btn {
  display: inline-block;
  *display: inline;
  padding: 4px 14px;
  margin-bottom: 0;
  *margin-left: .3em;
  font-size: 14px;
  line-height: 20px;
  *line-height: 20px;
  color: #333333;
  text-align: center;
  text-shadow: 0 1px 1px rgba(255, 255, 255, 0.75);
  vertical-align: middle;
  cursor: pointer;
  background-color: #f5f5f5;
  *background-color: #e6e6e6;
  background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#ffffff), to(#e6e6e6));
  background-image: -webkit-linear-gradient(top, #ffffff, #e6e6e6);
  background-image: -o-linear-gradient(top, #ffffff, #e6e6e6);
  background-image: linear-gradient(to bottom, #ffffff, #e6e6e6);
  background-image: -moz-linear-gradient(top, #ffffff, #e6e6e6);
  background-repeat: repeat-x;
  border: 1px solid #bbbbbb;
  *border: 0;
  border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
  border-color: #e6e6e6 #e6e6e6 #bfbfbf;
  border-bottom-color: #a2a2a2;
  -webkit-border-radius: 4px;
     -moz-border-radius: 4px;
          border-radius: 4px;
  filter: progid:dximagetransform.microsoft.gradient(startColorstr='#ffffffff', endColorstr='#ffe6e6e6', GradientType=0);
  filter: progid:dximagetransform.microsoft.gradient(enabled=false);
  *zoom: 1;
  -webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
     -moz-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
          box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
}

.btn:hover,
.btn:active,
.btn.active,
.btn.disabled,
.btn[disabled] {
  color: #333333;
  background-color: #e6e6e6;
  *background-color: #d9d9d9;
}

.btn:active,
.btn.active {
  background-color: #cccccc \9;
}

.btn:first-child {
  *margin-left: 0;
}

.btn:hover {
  color: #333333;
  text-decoration: none;
  background-color: #e6e6e6;
  *background-color: #d9d9d9;
  /* Buttons in IE7 don't get borders, so darken on hover */

  background-position: 0 -15px;
  -webkit-transition: background-position 0.1s linear;
     -moz-transition: background-position 0.1s linear;
       -o-transition: background-position 0.1s linear;
          transition: background-position 0.1s linear;
}

.btn:focus {
  outline: thin dotted #333;
  outline: 5px auto -webkit-focus-ring-color;
  outline-offset: -2px;
}

.btn.active,
.btn:active {
  background-color: #e6e6e6;
  background-color: #d9d9d9 \9;
  background-image: none;
  outline: 0;
  -webkit-box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
     -moz-box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
          box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
}

.btn.disabled,
.btn[disabled] {
  cursor: default;
  background-color: #e6e6e6;
  background-image: none;
  opacity: 0.65;
  filter: alpha(opacity=65);
  -webkit-box-shadow: none;
     -moz-box-shadow: none;
          box-shadow: none;
}

.btn-large {
  padding: 9px 14px;
  font-size: 16px;
  line-height: normal;
  -webkit-border-radius: 5px;
     -moz-border-radius: 5px;
          border-radius: 5px;
}

.btn-large [class^="icon-"] {
  margin-top: 2px;
}

.btn-small {
  padding: 3px 9px;
  font-size: 12px;
  line-height: 18px;
}

.btn-small [class^="icon-"] {
  margin-top: 0;
}

.btn-mini {
  padding: 2px 6px;
  font-size: 11px;
  line-height: 17px;
}

.btn-block {
  display: block;
  width: 100%;
  padding-right: 0;
  padding-left: 0;
  -webkit-box-sizing: border-box;
     -moz-box-sizing: border-box;
          box-sizing: border-box;
}

.btn-block + .btn-block {
  margin-top: 5px;
}

input[type="submit"].btn-block,
input[type="reset"].btn-block,
input[type="button"].btn-block {
  width: 100%;
}

.btn-primary.active,
.btn-warning.active,
.btn-danger.active,
.btn-success.active,
.btn-info.active,
.btn-inverse.active {
  color: rgba(255, 255, 255, 0.75);
}

.btn {
  border-color: #c5c5c5;
  border-color: rgba(0, 0, 0, 0.15) rgba(0, 0, 0, 0.15) rgba(0, 0, 0, 0.25);
}

.btn-primary {
  color: #ffffff;
  text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
  background-color: #006dcc;
  *background-color: #0044cc;
  background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#0088cc), to(#0044cc));
  background-image: -webkit-linear-gradient(top, #0088cc, #0044cc);
  background-image: -o-linear-gradient(top, #0088cc, #0044cc);
  background-image: linear-gradient(to bottom, #0088cc, #0044cc);
  background-image: -moz-linear-gradient(top, #0088cc, #0044cc);
  background-repeat: repeat-x;
  border-color: #0044cc #0044cc #002a80;
  border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
  filter: progid:dximagetransform.microsoft.gradient(startColorstr='#ff0088cc', endColorstr='#ff0044cc', GradientType=0);
  filter: progid:dximagetransform.microsoft.gradient(enabled=false);
}

.btn-primary:hover,
.btn-primary:active,
.btn-primary.active,
.btn-primary.disabled,
.btn-primary[disabled] {
  color: #ffffff;
  background-color: #0044cc;
  *background-color: #003bb3;
}

.btn-primary:active,
.btn-primary.active {
  background-color: #003399 \9;
}

.btn-warning {
  color: #ffffff;
  text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
  background-color: #faa732;
  *background-color: #f89406;
  background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#fbb450), to(#f89406));
  background-image: -webkit-linear-gradient(top, #fbb450, #f89406);
  background-image: -o-linear-gradient(top, #fbb450, #f89406);
  background-image: linear-gradient(to bottom, #fbb450, #f89406);
  background-image: -moz-linear-gradient(top, #fbb450, #f89406);
  background-repeat: repeat-x;
  border-color: #f89406 #f89406 #ad6704;
  border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
  filter: progid:dximagetransform.microsoft.gradient(startColorstr='#fffbb450', endColorstr='#fff89406', GradientType=0);
  filter: progid:dximagetransform.microsoft.gradient(enabled=false);
}

.btn-warning:hover,
.btn-warning:active,
.btn-warning.active,
.btn-warning.disabled,
.btn-warning[disabled] {
  color: #ffffff;
  background-color: #f89406;
  *background-color: #df8505;
}

.btn-warning:active,
.btn-warning.active {
  background-color: #c67605 \9;
}

.btn-danger {
  color: #ffffff;
  text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
  background-color: #da4f49;
  *background-color: #bd362f;
  background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#ee5f5b), to(#bd362f));
  background-image: -webkit-linear-gradient(top, #ee5f5b, #bd362f);
  background-image: -o-linear-gradient(top, #ee5f5b, #bd362f);
  background-image: linear-gradient(to bottom, #ee5f5b, #bd362f);
  background-image: -moz-linear-gradient(top, #ee5f5b, #bd362f);
  background-repeat: repeat-x;
  border-color: #bd362f #bd362f #802420;
  border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
  filter: progid:dximagetransform.microsoft.gradient(startColorstr='#ffee5f5b', endColorstr='#ffbd362f', GradientType=0);
  filter: progid:dximagetransform.microsoft.gradient(enabled=false);
}

.btn-danger:hover,
.btn-danger:active,
.btn-danger.active,
.btn-danger.disabled,
.btn-danger[disabled] {
  color: #ffffff;
  background-color: #bd362f;
  *background-color: #a9302a;
}

.btn-danger:active,
.btn-danger.active {
  background-color: #942a25 \9;
}

.btn-success {
  color: #ffffff;
  text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
  background-color: #5bb75b;
  *background-color: #51a351;
  background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#62c462), to(#51a351));
  background-image: -webkit-linear-gradient(top, #62c462, #51a351);
  background-image: -o-linear-gradient(top, #62c462, #51a351);
  background-image: linear-gradient(to bottom, #62c462, #51a351);
  background-image: -moz-linear-gradient(top, #62c462, #51a351);
  background-repeat: repeat-x;
  border-color: #51a351 #51a351 #387038;
  border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
  filter: progid:dximagetransform.microsoft.gradient(startColorstr='#ff62c462', endColorstr='#ff51a351', GradientType=0);
  filter: progid:dximagetransform.microsoft.gradient(enabled=false);
}

.btn-success:hover,
.btn-success:active,
.btn-success.active,
.btn-success.disabled,
.btn-success[disabled] {
  color: #ffffff;
  background-color: #51a351;
  *background-color: #499249;
}

.btn-success:active,
.btn-success.active {
  background-color: #408140 \9;
}

.btn-info {
  color: #ffffff;
  text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
  background-color: #49afcd;
  *background-color: #2f96b4;
  background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#5bc0de), to(#2f96b4));
  background-image: -webkit-linear-gradient(top, #5bc0de, #2f96b4);
  background-image: -o-linear-gradient(top, #5bc0de, #2f96b4);
  background-image: linear-gradient(to bottom, #5bc0de, #2f96b4);
  background-image: -moz-linear-gradient(top, #5bc0de, #2f96b4);
  background-repeat: repeat-x;
  border-color: #2f96b4 #2f96b4 #1f6377;
  border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
  filter: progid:dximagetransform.microsoft.gradient(startColorstr='#ff5bc0de', endColorstr='#ff2f96b4', GradientType=0);
  filter: progid:dximagetransform.microsoft.gradient(enabled=false);
}

.btn-info:hover,
.btn-info:active,
.btn-info.active,
.btn-info.disabled,
.btn-info[disabled] {
  color: #ffffff;
  background-color: #2f96b4;
  *background-color: #2a85a0;
}

.btn-info:active,
.btn-info.active {
  background-color: #24748c \9;
}

.btn-inverse {
  color: #ffffff;
  text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
  background-color: #363636;
  *background-color: #222222;
  background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#444444), to(#222222));
  background-image: -webkit-linear-gradient(top, #444444, #222222);
  background-image: -o-linear-gradient(top, #444444, #222222);
  background-image: linear-gradient(to bottom, #444444, #222222);
  background-image: -moz-linear-gradient(top, #444444, #222222);
  background-repeat: repeat-x;
  border-color: #222222 #222222 #000000;
  border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
  filter: progid:dximagetransform.microsoft.gradient(startColorstr='#ff444444', endColorstr='#ff222222', GradientType=0);
  filter: progid:dximagetransform.microsoft.gradient(enabled=false);
}

.btn-inverse:hover,
.btn-inverse:active,
.btn-inverse.active,
.btn-inverse.disabled,
.btn-inverse[disabled] {
  color: #ffffff;
  background-color: #222222;
  *background-color: #151515;
}

.btn-inverse:active,
.btn-inverse.active {
  background-color: #080808 \9;
}

button.btn,
input[type="submit"].btn {
  *padding-top: 3px;
  *padding-bottom: 3px;
}

button.btn::-moz-focus-inner,
input[type="submit"].btn::-moz-focus-inner {
  padding: 0;
  border: 0;
}

button.btn.btn-large,
input[type="submit"].btn.btn-large {
  *padding-top: 7px;
  *padding-bottom: 7px;
}

button.btn.btn-small,
input[type="submit"].btn.btn-small {
  *padding-top: 3px;
  *padding-bottom: 3px;
}

button.btn.btn-mini,
input[type="submit"].btn.btn-mini {
  *padding-top: 1px;
  *padding-bottom: 1px;
}

.btn-link,
.btn-link:active,
.btn-link[disabled] {
  background-color: transparent;
  background-image: none;
  -webkit-box-shadow: none;
     -moz-box-shadow: none;
          box-shadow: none;
}

.btn-link {
  color: #0088cc;
  cursor: pointer;
  border-color: transparent;
  -webkit-border-radius: 0;
     -moz-border-radius: 0;
          border-radius: 0;
}

.btn-link:hover {
  color: #005580;
  text-decoration: underline;
  background-color: transparent;
}

.btn-link[disabled]:hover {
  color: #333333;
  text-decoration: none;
}

.btn-group {
  position: relative;
  *margin-left: .3em;
  font-size: 0;
  white-space: nowrap;
  vertical-align: middle;
}

.btn-group:first-child {
  *margin-left: 0;
}

.btn-group + .btn-group {
  margin-left: 5px;
}

.btn-toolbar {
  margin-top: 10px;
  margin-bottom: 10px;
  font-size: 0;
}

.btn-toolbar .btn-group {
  display: inline-block;
  *display: inline;
  /* IE7 inline-block hack */

  *zoom: 1;
}

.btn-toolbar .btn + .btn,
.btn-toolbar .btn-group + .btn,
.btn-toolbar .btn + .btn-group {
  margin-left: 5px;
}

.btn-group > .btn {
  position: relative;
  -webkit-border-radius: 0;
     -moz-border-radius: 0;
          border-radius: 0;
}

.btn-group > .btn + .btn {
  margin-left: -1px;
}

.btn-group > .btn,
.btn-group > .dropdown-menu {
  font-size: 14px;
}

.btn-group > .btn-mini {
  font-size: 11px;
}

.btn-group > .btn-small {
  font-size: 12px;
}

.btn-group > .btn-large {
  font-size: 16px;
}

.btn-group > .btn:first-child {
  margin-left: 0;
  -webkit-border-bottom-left-radius: 4px;
          border-bottom-left-radius: 4px;
  -webkit-border-top-left-radius: 4px;
          border-top-left-radius: 4px;
  -moz-border-radius-bottomleft: 4px;
  -moz-border-radius-topleft: 4px;
}

.btn-group > .btn:last-child,
.btn-group > .dropdown-toggle {
  -webkit-border-top-right-radius: 4px;
          border-top-right-radius: 4px;
  -webkit-border-bottom-right-radius: 4px;
          border-bottom-right-radius: 4px;
  -moz-border-radius-topright: 4px;
  -moz-border-radius-bottomright: 4px;
}

.btn-group > .btn.large:first-child {
  margin-left: 0;
  -webkit-border-bottom-left-radius: 6px;
          border-bottom-left-radius: 6px;
  -webkit-border-top-left-radius: 6px;
          border-top-left-radius: 6px;
  -moz-border-radius-bottomleft: 6px;
  -moz-border-radius-topleft: 6px;
}

.btn-group > .btn.large:last-child,
.btn-group > .large.dropdown-toggle {
  -webkit-border-top-right-radius: 6px;
          border-top-right-radius: 6px;
  -webkit-border-bottom-right-radius: 6px;
          border-bottom-right-radius: 6px;
  -moz-border-radius-topright: 6px;
  -moz-border-radius-bottomright: 6px;
}

.btn-group > .btn:hover,
.btn-group > .btn:focus,
.btn-group > .btn:active,
.btn-group > .btn.active {
  z-index: 2;
}

.btn-group .dropdown-toggle:active,
.btn-group.open .dropdown-toggle {
  outline: 0;
}

.btn-group > .btn + .dropdown-toggle {
  *padding-top: 5px;
  padding-right: 8px;
  *padding-bottom: 5px;
  padding-left: 8px;
  -webkit-box-shadow: inset 1px 0 0 rgba(255, 255, 255, 0.125), inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
     -moz-box-shadow: inset 1px 0 0 rgba(255, 255, 255, 0.125), inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
          box-shadow: inset 1px 0 0 rgba(255, 255, 255, 0.125), inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
}

.btn-group > .btn-mini + .dropdown-toggle {
  *padding-top: 2px;
  padding-right: 5px;
  *padding-bottom: 2px;
  padding-left: 5px;
}

.btn-group > .btn-small + .dropdown-toggle {
  *padding-top: 5px;
  *padding-bottom: 4px;
}

.btn-group > .btn-large + .dropdown-toggle {
  *padding-top: 7px;
  padding-right: 12px;
  *padding-bottom: 7px;
  padding-left: 12px;
}

.btn-group.open .dropdown-toggle {
  background-image: none;
  -webkit-box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
     -moz-box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
          box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
}

.btn-group.open .btn.dropdown-toggle {
  background-color: #e6e6e6;
}

.btn-group.open .btn-primary.dropdown-toggle {
  background-color: #0044cc;
}

.btn-group.open .btn-warning.dropdown-toggle {
  background-color: #f89406;
}

.btn-group.open .btn-danger.dropdown-toggle {
  background-color: #bd362f;
}

.btn-group.open .btn-success.dropdown-toggle {
  background-color: #51a351;
}

.btn-group.open .btn-info.dropdown-toggle {
  background-color: #2f96b4;
}

.btn-group.open .btn-inverse.dropdown-toggle {
  background-color: #222222;
}

.btn .caret {
  margin-top: 8px;
  margin-left: 0;
}

.btn-mini .caret,
.btn-small .caret,
.btn-large .caret {
  margin-top: 6px;
}

.btn-large .caret {
  border-top-width: 5px;
  border-right-width: 5px;
  border-left-width: 5px;
}

.dropup .btn-large .caret {
  border-top: 0;
  border-bottom: 5px solid #000000;
}

.btn-primary .caret,
.btn-warning .caret,
.btn-danger .caret,
.btn-info .caret,
.btn-success .caret,
.btn-inverse .caret {
  border-top-color: #ffffff;
  border-bottom-color: #ffffff;
}

.btn-group-vertical {
  display: inline-block;
  *display: inline;
  /* IE7 inline-block hack */

  *zoom: 1;
}

.btn-group-vertical .btn {
  display: block;
  float: none;
  width: 100%;
  -webkit-border-radius: 0;
     -moz-border-radius: 0;
          border-radius: 0;
}

.btn-group-vertical .btn + .btn {
  margin-top: -1px;
  margin-left: 0;
}

.btn-group-vertical .btn:first-child {
  -webkit-border-radius: 4px 4px 0 0;
     -moz-border-radius: 4px 4px 0 0;
          border-radius: 4px 4px 0 0;
}

.btn-group-vertical .btn:last-child {
  -webkit-border-radius: 0 0 4px 4px;
     -moz-border-radius: 0 0 4px 4px;
          border-radius: 0 0 4px 4px;
}

.btn-group-vertical .btn-large:first-child {
  -webkit-border-radius: 6px 6px 0 0;
     -moz-border-radius: 6px 6px 0 0;
          border-radius: 6px 6px 0 0;
}

.btn-group-vertical .btn-large:last-child {
  -webkit-border-radius: 0 0 6px 6px;
     -moz-border-radius: 0 0 6px 6px;
          border-radius: 0 0 6px 6px;
}

.alert {
  padding: 8px 35px 8px 14px;
  margin-bottom: 20px;
  color: #c09853;
  text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);
  background-color: #fcf8e3;
  border: 1px solid #fbeed5;
  -webkit-border-radius: 4px;
     -moz-border-radius: 4px;
          border-radius: 4px;
}

.alert h4 {
  margin: 0;
}

.alert .close {
  position: relative;
  top: -2px;
  right: -21px;
  line-height: 20px;
}

.alert-success {
  color: #468847;
  background-color: #dff0d8;
  border-color: #d6e9c6;
}

.alert-danger,
.alert-error {
  color: #b94a48;
  background-color: #f2dede;
  border-color: #eed3d7;
}

.alert-info {
  color: #3a87ad;
  background-color: #d9edf7;
  border-color: #bce8f1;
}

.alert-block {
  padding-top: 14px;
  padding-bottom: 14px;
}

.alert-block > p,
.alert-block > ul {
  margin-bottom: 0;
}

.alert-block p + p {
  margin-top: 5px;
}

.nav {
  margin-bottom: 20px;
  margin-left: 0;
  list-style: none;
}

.nav > li > a {
  display: block;
}

.nav > li > a:hover {
  text-decoration: none;
  background-color: #eeeeee;
}

.nav > .pull-right {
  float: right;
}

.nav-header {
  display: block;
  padding: 3px 15px;
  font-size: 11px;
  font-weight: bold;
  line-height: 20px;
  color: #999999;
  text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);
  text-transform: uppercase;
}

.nav li + .nav-header {
  margin-top: 9px;
}

.nav-list {
  padding-right: 15px;
  padding-left: 15px;
  margin-bottom: 0;
}

.nav-list > li > a,
.nav-list .nav-header {
  margin-right: -15px;
  margin-left: -15px;
  text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);
}

.nav-list > li > a {
  padding: 3px 15px;
}

.nav-list > .active > a,
.nav-list > .active > a:hover {
  color: #ffffff;
  text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.2);
  background-color: #0088cc;
}

.nav-list [class^="icon-"] {
  margin-right: 2px;
}

.nav-list .divider {
  *width: 100%;
  height: 1px;
  margin: 9px 1px;
  *margin: -5px 0 5px;
  overflow: hidden;
  background-color: #e5e5e5;
  border-bottom: 1px solid #ffffff;
}

.nav-tabs,
.nav-pills {
  *zoom: 1;
}

.nav-tabs:before,
.nav-pills:before,
.nav-tabs:after,
.nav-pills:after {
  display: table;
  line-height: 0;
  content: "";
}

.nav-tabs:after,
.nav-pills:after {
  clear: both;
}

.nav-tabs > li,
.nav-pills > li {
  float: left;
}

.nav-tabs > li > a,
.nav-pills > li > a {
  padding-right: 12px;
  padding-left: 12px;
  margin-right: 2px;
  line-height: 14px;
}

.nav-tabs {
  border-bottom: 1px solid #ddd;
}

.nav-tabs > li {
  margin-bottom: -1px;
}

.nav-tabs > li > a {
  padding-top: 8px;
  padding-bottom: 8px;
  line-height: 20px;
  border: 1px solid transparent;
  -webkit-border-radius: 4px 4px 0 0;
     -moz-border-radius: 4px 4px 0 0;
          border-radius: 4px 4px 0 0;
}

.nav-tabs > li > a:hover {
  border-color: #eeeeee #eeeeee #dddddd;
}

.nav-tabs > .active > a,
.nav-tabs > .active > a:hover {
  color: #555555;
  cursor: default;
  background-color: #ffffff;
  border: 1px solid #ddd;
  border-bottom-color: transparent;
}

.nav-pills > li > a {
  padding-top: 8px;
  padding-bottom: 8px;
  margin-top: 2px;
  margin-bottom: 2px;
  -webkit-border-radius: 5px;
     -moz-border-radius: 5px;
          border-radius: 5px;
}

.nav-pills > .active > a,
.nav-pills > .active > a:hover {
  color: #ffffff;
  background-color: #0088cc;
}

.nav-stacked > li {
  float: none;
}

.nav-stacked > li > a {
  margin-right: 0;
}

.nav-tabs.nav-stacked {
  border-bottom: 0;
}

.nav-tabs.nav-stacked > li > a {
  border: 1px solid #ddd;
  -webkit-border-radius: 0;
     -moz-border-radius: 0;
          border-radius: 0;
}

.nav-tabs.nav-stacked > li:first-child > a {
  -webkit-border-top-right-radius: 4px;
          border-top-right-radius: 4px;
  -webkit-border-top-left-radius: 4px;
          border-top-left-radius: 4px;
  -moz-border-radius-topright: 4px;
  -moz-border-radius-topleft: 4px;
}

.nav-tabs.nav-stacked > li:last-child > a {
  -webkit-border-bottom-right-radius: 4px;
          border-bottom-right-radius: 4px;
  -webkit-border-bottom-left-radius: 4px;
          border-bottom-left-radius: 4px;
  -moz-border-radius-bottomright: 4px;
  -moz-border-radius-bottomleft: 4px;
}

.nav-tabs.nav-stacked > li > a:hover {
  z-index: 2;
  border-color: #ddd;
}

.nav-pills.nav-stacked > li > a {
  margin-bottom: 3px;
}

.nav-pills.nav-stacked > li:last-child > a {
  margin-bottom: 1px;
}

.nav-tabs .dropdown-menu {
  -webkit-border-radius: 0 0 6px 6px;
     -moz-border-radius: 0 0 6px 6px;
          border-radius: 0 0 6px 6px;
}

.nav-pills .dropdown-menu {
  -webkit-border-radius: 6px;
     -moz-border-radius: 6px;
          border-radius: 6px;
}

.nav .dropdown-toggle .caret {
  margin-top: 6px;
  border-top-color: #0088cc;
  border-bottom-color: #0088cc;
}

.nav .dropdown-toggle:hover .caret {
  border-top-color: #005580;
  border-bottom-color: #005580;
}

/* move down carets for tabs */

.nav-tabs .dropdown-toggle .caret {
  margin-top: 8px;
}

.nav .active .dropdown-toggle .caret {
  border-top-color: #fff;
  border-bottom-color: #fff;
}

.nav-tabs .active .dropdown-toggle .caret {
  border-top-color: #555555;
  border-bottom-color: #555555;
}

.nav > .dropdown.active > a:hover {
  cursor: pointer;
}

.nav-tabs .open .dropdown-toggle,
.nav-pills .open .dropdown-toggle,
.nav > li.dropdown.open.active > a:hover {
  color: #ffffff;
  background-color: #999999;
  border-color: #999999;
}

.nav li.dropdown.open .caret,
.nav li.dropdown.open.active .caret,
.nav li.dropdown.open a:hover .caret {
  border-top-color: #ffffff;
  border-bottom-color: #ffffff;
  opacity: 1;
  filter: alpha(opacity=100);
}

.tabs-stacked .open > a:hover {
  border-color: #999999;
}

.tabbable {
  *zoom: 1;
}

.tabbable:before,
.tabbable:after {
  display: table;
  line-height: 0;
  content: "";
}

.tabbable:after {
  clear: both;
}

.tab-content {
  overflow: auto;
}

.tabs-below > .nav-tabs,
.tabs-right > .nav-tabs,
.tabs-left > .nav-tabs {
  border-bottom: 0;
}

.tab-content > .tab-pane,
.pill-content > .pill-pane {
  display: none;
}

.tab-content > .active,
.pill-content > .active {
  display: block;
}

.tabs-below > .nav-tabs {
  border-top: 1px solid #ddd;
}

.tabs-below > .nav-tabs > li {
  margin-top: -1px;
  margin-bottom: 0;
}

.tabs-below > .nav-tabs > li > a {
  -webkit-border-radius: 0 0 4px 4px;
     -moz-border-radius: 0 0 4px 4px;
          border-radius: 0 0 4px 4px;
}

.tabs-below > .nav-tabs > li > a:hover {
  border-top-color: #ddd;
  border-bottom-color: transparent;
}

.tabs-below > .nav-tabs > .active > a,
.tabs-below > .nav-tabs > .active > a:hover {
  border-color: transparent #ddd #ddd #ddd;
}

.tabs-left > .nav-tabs > li,
.tabs-right > .nav-tabs > li {
  float: none;
}

.tabs-left > .nav-tabs > li > a,
.tabs-right > .nav-tabs > li > a {
  min-width: 74px;
  margin-right: 0;
  margin-bottom: 3px;
}

.tabs-left > .nav-tabs {
  float: left;
  margin-right: 19px;
  border-right: 1px solid #ddd;
}

.tabs-left > .nav-tabs > li > a {
  margin-right: -1px;
  -webkit-border-radius: 4px 0 0 4px;
     -moz-border-radius: 4px 0 0 4px;
          border-radius: 4px 0 0 4px;
}

.tabs-left > .nav-tabs > li > a:hover {
  border-color: #eeeeee #dddddd #eeeeee #eeeeee;
}

.tabs-left > .nav-tabs .active > a,
.tabs-left > .nav-tabs .active > a:hover {
  border-color: #ddd transparent #ddd #ddd;
  *border-right-color: #ffffff;
}

.tabs-right > .nav-tabs {
  float: right;
  margin-left: 19px;
  border-left: 1px solid #ddd;
}

.tabs-right > .nav-tabs > li > a {
  margin-left: -1px;
  -webkit-border-radius: 0 4px 4px 0;
     -moz-border-radius: 0 4px 4px 0;
          border-radius: 0 4px 4px 0;
}

.tabs-right > .nav-tabs > li > a:hover {
  border-color: #eeeeee #eeeeee #eeeeee #dddddd;
}

.tabs-right > .nav-tabs .active > a,
.tabs-right > .nav-tabs .active > a:hover {
  border-color: #ddd #ddd #ddd transparent;
  *border-left-color: #ffffff;
}

.nav > .disabled > a {
  color: #999999;
}

.nav > .disabled > a:hover {
  text-decoration: none;
  cursor: default;
  background-color: transparent;
}

.navbar {
  *position: relative;
  *z-index: 2;
  margin-bottom: 20px;
  overflow: visible;
  color: #777777;
}

.navbar-inner {
  min-height: 40px;
  padding-right: 20px;
  padding-left: 20px;
  background-color: #fafafa;
  background-image: -moz-linear-gradient(top, #ffffff, #f2f2f2);
  background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#ffffff), to(#f2f2f2));
  background-image: -webkit-linear-gradient(top, #ffffff, #f2f2f2);
  background-image: -o-linear-gradient(top, #ffffff, #f2f2f2);
  background-image: linear-gradient(to bottom, #ffffff, #f2f2f2);
  background-repeat: repeat-x;
  border: 1px solid #d4d4d4;
  -webkit-border-radius: 4px;
     -moz-border-radius: 4px;
          border-radius: 4px;
  filter: progid:dximagetransform.microsoft.gradient(startColorstr='#ffffffff', endColorstr='#fff2f2f2', GradientType=0);
  *zoom: 1;
  -webkit-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.065);
     -moz-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.065);
          box-shadow: 0 1px 4px rgba(0, 0, 0, 0.065);
}

.navbar-inner:before,
.navbar-inner:after {
  display: table;
  line-height: 0;
  content: "";
}

.navbar-inner:after {
  clear: both;
}

.navbar .container {
  width: auto;
}

.nav-collapse.collapse {
  height: auto;
}

.navbar .brand {
  display: block;
  float: left;
  padding: 10px 20px 10px;
  margin-left: -20px;
  font-size: 20px;
  font-weight: 200;
  color: #777777;
  text-shadow: 0 1px 0 #ffffff;
}

.navbar .brand:hover {
  text-decoration: none;
}

.navbar-text {
  margin-bottom: 0;
  line-height: 40px;
}

.navbar-link {
  color: #777777;
}

.navbar-link:hover {
  color: #333333;
}

.navbar .divider-vertical {
  height: 40px;
  margin: 0 9px;
  border-right: 1px solid #ffffff;
  border-left: 1px solid #f2f2f2;
}

.navbar .btn,
.navbar .btn-group {
  margin-top: 5px;
}

.navbar .btn-group .btn,
.navbar .input-prepend .btn,
.navbar .input-append .btn {
  margin-top: 0;
}

.navbar-form {
  margin-bottom: 0;
  *zoom: 1;
}

.navbar-form:before,
.navbar-form:after {
  display: table;
  line-height: 0;
  content: "";
}

.navbar-form:after {
  clear: both;
}

.navbar-form input,
.navbar-form select,
.navbar-form .radio,
.navbar-form .checkbox {
  margin-top: 5px;
}

.navbar-form input,
.navbar-form select,
.navbar-form .btn {
  display: inline-block;
  margin-bottom: 0;
}

.navbar-form input[type="image"],
.navbar-form input[type="checkbox"],
.navbar-form input[type="radio"] {
  margin-top: 3px;
}

.navbar-form .input-append,
.navbar-form .input-prepend {
  margin-top: 6px;
  white-space: nowrap;
}

.navbar-form .input-append input,
.navbar-form .input-prepend input {
  margin-top: 0;
}

.navbar-search {
  position: relative;
  float: left;
  margin-top: 5px;
  margin-bottom: 0;
}

.navbar-search .search-query {
  padding: 4px 14px;
  margin-bottom: 0;
  font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
  font-size: 13px;
  font-weight: normal;
  line-height: 1;
  -webkit-border-radius: 15px;
     -moz-border-radius: 15px;
          border-radius: 15px;
}

.navbar-static-top {
  position: static;
  width: 100%;
  margin-bottom: 0;
}

.navbar-static-top .navbar-inner {
  -webkit-border-radius: 0;
     -moz-border-radius: 0;
          border-radius: 0;
}

.navbar-fixed-top,
.navbar-fixed-bottom {
  position: fixed;
  right: 0;
  left: 0;
  z-index: 1030;
  margin-bottom: 0;
}

.navbar-fixed-top .navbar-inner,
.navbar-static-top .navbar-inner {
  border-width: 0 0 1px;
}

.navbar-fixed-bottom .navbar-inner {
  border-width: 1px 0 0;
}

.navbar-fixed-top .navbar-inner,
.navbar-fixed-bottom .navbar-inner {
  padding-right: 0;
  padding-left: 0;
  -webkit-border-radius: 0;
     -moz-border-radius: 0;
          border-radius: 0;
}

.navbar-static-top .container,
.navbar-fixed-top .container,
.navbar-fixed-bottom .container {
  width: 940px;
}

.navbar-fixed-top {
  top: 0;
}

.navbar-fixed-top .navbar-inner,
.navbar-static-top .navbar-inner {
  -webkit-box-shadow: inset 0 -1px 0 rgba(0, 0, 0, 0.1), 0 1px 10px rgba(0, 0, 0, 0.1);
     -moz-box-shadow: inset 0 -1px 0 rgba(0, 0, 0, 0.1), 0 1px 10px rgba(0, 0, 0, 0.1);
          box-shadow: inset 0 -1px 0 rgba(0, 0, 0, 0.1), 0 1px 10px rgba(0, 0, 0, 0.1);
}

.navbar-fixed-bottom {
  bottom: 0;
}

.navbar-fixed-bottom .navbar-inner {
  -webkit-box-shadow: inset 0 1px 0 rgba(0, 0, 0, 0.1), 0 -1px 10px rgba(0, 0, 0, 0.1);
     -moz-box-shadow: inset 0 1px 0 rgba(0, 0, 0, 0.1), 0 -1px 10px rgba(0, 0, 0, 0.1);
          box-shadow: inset 0 1px 0 rgba(0, 0, 0, 0.1), 0 -1px 10px rgba(0, 0, 0, 0.1);
}

.navbar .nav {
  position: relative;
  left: 0;
  display: block;
  float: left;
  margin: 0 10px 0 0;
}

.navbar .nav.pull-right {
  float: right;
  margin-right: 0;
}

.navbar .nav > li {
  float: left;
}

.navbar .nav > li > a {
  float: none;
  padding: 10px 15px 10px;
  color: #777777;
  text-decoration: none;
  text-shadow: 0 1px 0 #ffffff;
}

.navbar .nav .dropdown-toggle .caret {
  margin-top: 8px;
}

.navbar .nav > li > a:focus,
.navbar .nav > li > a:hover {
  color: #333333;
  text-decoration: none;
  background-color: transparent;
}

.navbar .nav > .active > a,
.navbar .nav > .active > a:hover,
.navbar .nav > .active > a:focus {
  color: #555555;
  text-decoration: none;
  background-color: #e5e5e5;
  -webkit-box-shadow: inset 0 3px 8px rgba(0, 0, 0, 0.125);
     -moz-box-shadow: inset 0 3px 8px rgba(0, 0, 0, 0.125);
          box-shadow: inset 0 3px 8px rgba(0, 0, 0, 0.125);
}

.navbar .btn-navbar {
  display: none;
  float: right;
  padding: 7px 10px;
  margin-right: 5px;
  margin-left: 5px;
  color: #ffffff;
  text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
  background-color: #ededed;
  *background-color: #e5e5e5;
  background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#f2f2f2), to(#e5e5e5));
  background-image: -webkit-linear-gradient(top, #f2f2f2, #e5e5e5);
  background-image: -o-linear-gradient(top, #f2f2f2, #e5e5e5);
  background-image: linear-gradient(to bottom, #f2f2f2, #e5e5e5);
  background-image: -moz-linear-gradient(top, #f2f2f2, #e5e5e5);
  background-repeat: repeat-x;
  border-color: #e5e5e5 #e5e5e5 #bfbfbf;
  border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
  filter: progid:dximagetransform.microsoft.gradient(startColorstr='#fff2f2f2', endColorstr='#ffe5e5e5', GradientType=0);
  filter: progid:dximagetransform.microsoft.gradient(enabled=false);
  -webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.1), 0 1px 0 rgba(255, 255, 255, 0.075);
     -moz-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.1), 0 1px 0 rgba(255, 255, 255, 0.075);
          box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.1), 0 1px 0 rgba(255, 255, 255, 0.075);
}

.navbar .btn-navbar:hover,
.navbar .btn-navbar:active,
.navbar .btn-navbar.active,
.navbar .btn-navbar.disabled,
.navbar .btn-navbar[disabled] {
  color: #ffffff;
  background-color: #e5e5e5;
  *background-color: #d9d9d9;
}

.navbar .btn-navbar:active,
.navbar .btn-navbar.active {
  background-color: #cccccc \9;
}

.navbar .btn-navbar .icon-bar {
  display: block;
  width: 18px;
  height: 2px;
  background-color: #f5f5f5;
  -webkit-border-radius: 1px;
     -moz-border-radius: 1px;
          border-radius: 1px;
  -webkit-box-shadow: 0 1px 0 rgba(0, 0, 0, 0.25);
     -moz-box-shadow: 0 1px 0 rgba(0, 0, 0, 0.25);
          box-shadow: 0 1px 0 rgba(0, 0, 0, 0.25);
}

.btn-navbar .icon-bar + .icon-bar {
  margin-top: 3px;
}

.navbar .nav > li > .dropdown-menu:before {
  position: absolute;
  top: -7px;
  left: 9px;
  display: inline-block;
  border-right: 7px solid transparent;
  border-bottom: 7px solid #ccc;
  border-left: 7px solid transparent;
  border-bottom-color: rgba(0, 0, 0, 0.2);
  content: '';
}

.navbar .nav > li > .dropdown-menu:after {
  position: absolute;
  top: -6px;
  left: 10px;
  display: inline-block;
  border-right: 6px solid transparent;
  border-bottom: 6px solid #ffffff;
  border-left: 6px solid transparent;
  content: '';
}

.navbar-fixed-bottom .nav > li > .dropdown-menu:before {
  top: auto;
  bottom: -7px;
  border-top: 7px solid #ccc;
  border-bottom: 0;
  border-top-color: rgba(0, 0, 0, 0.2);
}

.navbar-fixed-bottom .nav > li > .dropdown-menu:after {
  top: auto;
  bottom: -6px;
  border-top: 6px solid #ffffff;
  border-bottom: 0;
}

.navbar .nav li.dropdown.open > .dropdown-toggle,
.navbar .nav li.dropdown.active > .dropdown-toggle,
.navbar .nav li.dropdown.open.active > .dropdown-toggle {
  color: #555555;
  background-color: #e5e5e5;
}

.navbar .nav li.dropdown > .dropdown-toggle .caret {
  border-top-color: #777777;
  border-bottom-color: #777777;
}

.navbar .nav li.dropdown.open > .dropdown-toggle .caret,
.navbar .nav li.dropdown.active > .dropdown-toggle .caret,
.navbar .nav li.dropdown.open.active > .dropdown-toggle .caret {
  border-top-color: #555555;
  border-bottom-color: #555555;
}

.navbar .pull-right > li > .dropdown-menu,
.navbar .nav > li > .dropdown-menu.pull-right {
  right: 0;
  left: auto;
}

.navbar .pull-right > li > .dropdown-menu:before,
.navbar .nav > li > .dropdown-menu.pull-right:before {
  right: 12px;
  left: auto;
}

.navbar .pull-right > li > .dropdown-menu:after,
.navbar .nav > li > .dropdown-menu.pull-right:after {
  right: 13px;
  left: auto;
}

.navbar .pull-right > li > .dropdown-menu .dropdown-menu,
.navbar .nav > li > .dropdown-menu.pull-right .dropdown-menu {
  right: 100%;
  left: auto;
  margin-right: -1px;
  margin-left: 0;
  -webkit-border-radius: 6px 0 6px 6px;
     -moz-border-radius: 6px 0 6px 6px;
          border-radius: 6px 0 6px 6px;
}

.navbar-inverse {
  color: #999999;
}

.navbar-inverse .navbar-inner {
  background-color: #1b1b1b;
  background-image: -moz-linear-gradient(top, #222222, #111111);
  background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#222222), to(#111111));
  background-image: -webkit-linear-gradient(top, #222222, #111111);
  background-image: -o-linear-gradient(top, #222222, #111111);
  background-image: linear-gradient(to bottom, #222222, #111111);
  background-repeat: repeat-x;
  border-color: #252525;
  filter: progid:dximagetransform.microsoft.gradient(startColorstr='#ff222222', endColorstr='#ff111111', GradientType=0);
}

.navbar-inverse .brand,
.navbar-inverse .nav > li > a {
  color: #999999;
  text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
}

.navbar-inverse .brand:hover,
.navbar-inverse .nav > li > a:hover {
  color: #ffffff;
}

.navbar-inverse .nav > li > a:focus,
.navbar-inverse .nav > li > a:hover {
  color: #ffffff;
  background-color: transparent;
}

.navbar-inverse .nav .active > a,
.navbar-inverse .nav .active > a:hover,
.navbar-inverse .nav .active > a:focus {
  color: #ffffff;
  background-color: #111111;
}

.navbar-inverse .navbar-link {
  color: #999999;
}

.navbar-inverse .navbar-link:hover {
  color: #ffffff;
}

.navbar-inverse .divider-vertical {
  border-right-color: #222222;
  border-left-color: #111111;
}

.navbar-inverse .nav li.dropdown.open > .dropdown-toggle,
.navbar-inverse .nav li.dropdown.active > .dropdown-toggle,
.navbar-inverse .nav li.dropdown.open.active > .dropdown-toggle {
  color: #ffffff;
  background-color: #111111;
}

.navbar-inverse .nav li.dropdown > .dropdown-toggle .caret {
  border-top-color: #999999;
  border-bottom-color: #999999;
}

.navbar-inverse .nav li.dropdown.open > .dropdown-toggle .caret,
.navbar-inverse .nav li.dropdown.active > .dropdown-toggle .caret,
.navbar-inverse .nav li.dropdown.open.active > .dropdown-toggle .caret {
  border-top-color: #ffffff;
  border-bottom-color: #ffffff;
}

.navbar-inverse .navbar-search .search-query {
  color: #ffffff;
  background-color: #515151;
  border-color: #111111;
  -webkit-box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1), 0 1px 0 rgba(255, 255, 255, 0.15);
     -moz-box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1), 0 1px 0 rgba(255, 255, 255, 0.15);
          box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1), 0 1px 0 rgba(255, 255, 255, 0.15);
  -webkit-transition: none;
     -moz-transition: none;
       -o-transition: none;
          transition: none;
}

.navbar-inverse .navbar-search .search-query:-moz-placeholder {
  color: #cccccc;
}

.navbar-inverse .navbar-search .search-query:-ms-input-placeholder {
  color: #cccccc;
}

.navbar-inverse .navbar-search .search-query::-webkit-input-placeholder {
  color: #cccccc;
}

.navbar-inverse .navbar-search .search-query:focus,
.navbar-inverse .navbar-search .search-query.focused {
  padding: 5px 15px;
  color: #333333;
  text-shadow: 0 1px 0 #ffffff;
  background-color: #ffffff;
  border: 0;
  outline: 0;
  -webkit-box-shadow: 0 0 3px rgba(0, 0, 0, 0.15);
     -moz-box-shadow: 0 0 3px rgba(0, 0, 0, 0.15);
          box-shadow: 0 0 3px rgba(0, 0, 0, 0.15);
}

.navbar-inverse .btn-navbar {
  color: #ffffff;
  text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
  background-color: #0e0e0e;
  *background-color: #040404;
  background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#151515), to(#040404));
  background-image: -webkit-linear-gradient(top, #151515, #040404);
  background-image: -o-linear-gradient(top, #151515, #040404);
  background-image: linear-gradient(to bottom, #151515, #040404);
  background-image: -moz-linear-gradient(top, #151515, #040404);
  background-repeat: repeat-x;
  border-color: #040404 #040404 #000000;
  border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
  filter: progid:dximagetransform.microsoft.gradient(startColorstr='#ff151515', endColorstr='#ff040404', GradientType=0);
  filter: progid:dximagetransform.microsoft.gradient(enabled=false);
}

.navbar-inverse .btn-navbar:hover,
.navbar-inverse .btn-navbar:active,
.navbar-inverse .btn-navbar.active,
.navbar-inverse .btn-navbar.disabled,
.navbar-inverse .btn-navbar[disabled] {
  color: #ffffff;
  background-color: #040404;
  *background-color: #000000;
}

.navbar-inverse .btn-navbar:active,
.navbar-inverse .btn-navbar.active {
  background-color: #000000 \9;
}

.breadcrumb {
  padding: 8px 15px;
  margin: 0 0 20px;
  list-style: none;
  background-color: #f5f5f5;
  -webkit-border-radius: 4px;
     -moz-border-radius: 4px;
          border-radius: 4px;
}

.breadcrumb li {
  display: inline-block;
  *display: inline;
  text-shadow: 0 1px 0 #ffffff;
  *zoom: 1;
}

.breadcrumb .divider {
  padding: 0 5px;
  color: #ccc;
}

.breadcrumb .active {
  color: #999999;
}

.pagination {
  height: 40px;
  margin: 20px 0;
}

.pagination ul {
  display: inline-block;
  *display: inline;
  margin-bottom: 0;
  margin-left: 0;
  -webkit-border-radius: 3px;
     -moz-border-radius: 3px;
          border-radius: 3px;
  *zoom: 1;
  -webkit-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
     -moz-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
          box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

.pagination ul > li {
  display: inline;
}

.pagination ul > li > a,
.pagination ul > li > span {
  float: left;
  padding: 0 14px;
  line-height: 38px;
  text-decoration: none;
  background-color: #ffffff;
  border: 1px solid #dddddd;
  border-left-width: 0;
}

.pagination ul > li > a:hover,
.pagination ul > .active > a,
.pagination ul > .active > span {
  background-color: #f5f5f5;
}

.pagination ul > .active > a,
.pagination ul > .active > span {
  color: #999999;
  cursor: default;
}

.pagination ul > .disabled > span,
.pagination ul > .disabled > a,
.pagination ul > .disabled > a:hover {
  color: #999999;
  cursor: default;
  background-color: transparent;
}

.pagination ul > li:first-child > a,
.pagination ul > li:first-child > span {
  border-left-width: 1px;
  -webkit-border-radius: 3px 0 0 3px;
     -moz-border-radius: 3px 0 0 3px;
          border-radius: 3px 0 0 3px;
}

.pagination ul > li:last-child > a,
.pagination ul > li:last-child > span {
  -webkit-border-radius: 0 3px 3px 0;
     -moz-border-radius: 0 3px 3px 0;
          border-radius: 0 3px 3px 0;
}

.pagination-centered {
  text-align: center;
}

.pagination-right {
  text-align: right;
}

.pager {
  margin: 20px 0;
  text-align: center;
  list-style: none;
  *zoom: 1;
}

.pager:before,
.pager:after {
  display: table;
  line-height: 0;
  content: "";
}

.pager:after {
  clear: both;
}

.pager li {
  display: inline;
}

.pager a,
.pager span {
  display: inline-block;
  padding: 5px 14px;
  background-color: #fff;
  border: 1px solid #ddd;
  -webkit-border-radius: 15px;
     -moz-border-radius: 15px;
          border-radius: 15px;
}

.pager a:hover {
  text-decoration: none;
  background-color: #f5f5f5;
}

.pager .next a,
.pager .next span {
  float: right;
}

.pager .previous a {
  float: left;
}

.pager .disabled a,
.pager .disabled a:hover,
.pager .disabled span {
  color: #999999;
  cursor: default;
  background-color: #fff;
}

.modal-open .modal .dropdown-menu {
  z-index: 2050;
}

.modal-open .modal .dropdown.open {
  *z-index: 2050;
}

.modal-open .modal .popover {
  z-index: 2060;
}

.modal-open .modal .tooltip {
  z-index: 2080;
}

.modal-backdrop {
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  z-index: 1040;
  background-color: #000000;
}

.modal-backdrop.fade {
  opacity: 0;
}

.modal-backdrop,
.modal-backdrop.fade.in {
  opacity: 0.8;
  filter: alpha(opacity=80);
}

.modal {
  position: fixed;
  top: 50%;
  left: 50%;
  z-index: 1050;
  width: 560px;
  margin: -250px 0 0 -280px;
  overflow: auto;
  background-color: #ffffff;
  border: 1px solid #999;
  border: 1px solid rgba(0, 0, 0, 0.3);
  *border: 1px solid #999;
  -webkit-border-radius: 6px;
     -moz-border-radius: 6px;
          border-radius: 6px;
  -webkit-box-shadow: 0 3px 7px rgba(0, 0, 0, 0.3);
     -moz-box-shadow: 0 3px 7px rgba(0, 0, 0, 0.3);
          box-shadow: 0 3px 7px rgba(0, 0, 0, 0.3);
  -webkit-background-clip: padding-box;
     -moz-background-clip: padding-box;
          background-clip: padding-box;
}

.modal.fade {
  top: -25%;
  -webkit-transition: opacity 0.3s linear, top 0.3s ease-out;
     -moz-transition: opacity 0.3s linear, top 0.3s ease-out;
       -o-transition: opacity 0.3s linear, top 0.3s ease-out;
          transition: opacity 0.3s linear, top 0.3s ease-out;
}

.modal.fade.in {
  top: 50%;
}

.modal-header {
  padding: 9px 15px;
  border-bottom: 1px solid #eee;
}

.modal-header .close {
  margin-top: 2px;
}

.modal-header h3 {
  margin: 0;
  line-height: 30px;
}

.modal-body {
  max-height: 400px;
  padding: 15px;
  overflow-y: auto;
}

.modal-form {
  margin-bottom: 0;
}

.modal-footer {
  padding: 14px 15px 15px;
  margin-bottom: 0;
  text-align: right;
  background-color: #f5f5f5;
  border-top: 1px solid #ddd;
  -webkit-border-radius: 0 0 6px 6px;
     -moz-border-radius: 0 0 6px 6px;
          border-radius: 0 0 6px 6px;
  *zoom: 1;
  -webkit-box-shadow: inset 0 1px 0 #ffffff;
     -moz-box-shadow: inset 0 1px 0 #ffffff;
          box-shadow: inset 0 1px 0 #ffffff;
}

.modal-footer:before,
.modal-footer:after {
  display: table;
  line-height: 0;
  content: "";
}

.modal-footer:after {
  clear: both;
}

.modal-footer .btn + .btn {
  margin-bottom: 0;
  margin-left: 5px;
}

.modal-footer .btn-group .btn + .btn {
  margin-left: -1px;
}

.tooltip {
  position: absolute;
  z-index: 1030;
  display: block;
  padding: 5px;
  font-size: 11px;
  opacity: 0;
  filter: alpha(opacity=0);
  visibility: visible;
}

.tooltip.in {
  opacity: 0.8;
  filter: alpha(opacity=80);
}

.tooltip.top {
  margin-top: -3px;
}

.tooltip.right {
  margin-left: 3px;
}

.tooltip.bottom {
  margin-top: 3px;
}

.tooltip.left {
  margin-left: -3px;
}

.tooltip-inner {
  max-width: 200px;
  padding: 3px 8px;
  color: #ffffff;
  text-align: center;
  text-decoration: none;
  background-color: #000000;
  -webkit-border-radius: 4px;
     -moz-border-radius: 4px;
          border-radius: 4px;
}

.tooltip-arrow {
  position: absolute;
  width: 0;
  height: 0;
  border-color: transparent;
  border-style: solid;
}

.tooltip.top .tooltip-arrow {
  bottom: 0;
  left: 50%;
  margin-left: -5px;
  border-top-color: #000000;
  border-width: 5px 5px 0;
}

.tooltip.right .tooltip-arrow {
  top: 50%;
  left: 0;
  margin-top: -5px;
  border-right-color: #000000;
  border-width: 5px 5px 5px 0;
}

.tooltip.left .tooltip-arrow {
  top: 50%;
  right: 0;
  margin-top: -5px;
  border-left-color: #000000;
  border-width: 5px 0 5px 5px;
}

.tooltip.bottom .tooltip-arrow {
  top: 0;
  left: 50%;
  margin-left: -5px;
  border-bottom-color: #000000;
  border-width: 0 5px 5px;
}

.popover {
  position: absolute;
  top: 0;
  left: 0;
  z-index: 1010;
  display: none;
  width: 236px;
  padding: 1px;
  background-color: #ffffff;
  border: 1px solid #ccc;
  border: 1px solid rgba(0, 0, 0, 0.2);
  -webkit-border-radius: 6px;
     -moz-border-radius: 6px;
          border-radius: 6px;
  -webkit-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
     -moz-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
          box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
  -webkit-background-clip: padding-box;
     -moz-background-clip: padding;
          background-clip: padding-box;
}

.popover.top {
  margin-bottom: 10px;
}

.popover.right {
  margin-left: 10px;
}

.popover.bottom {
  margin-top: 10px;
}

.popover.left {
  margin-right: 10px;
}

.popover-title {
  padding: 8px 14px;
  margin: 0;
  font-size: 14px;
  font-weight: normal;
  line-height: 18px;
  background-color: #f7f7f7;
  border-bottom: 1px solid #ebebeb;
  -webkit-border-radius: 5px 5px 0 0;
     -moz-border-radius: 5px 5px 0 0;
          border-radius: 5px 5px 0 0;
}

.popover-content {
  padding: 9px 14px;
}

.popover-content p,
.popover-content ul,
.popover-content ol {
  margin-bottom: 0;
}

.popover .arrow,
.popover .arrow:after {
  position: absolute;
  display: inline-block;
  width: 0;
  height: 0;
  border-color: transparent;
  border-style: solid;
}

.popover .arrow:after {
  z-index: -1;
  content: "";
}

.popover.top .arrow {
  bottom: -10px;
  left: 50%;
  margin-left: -10px;
  border-top-color: #ffffff;
  border-width: 10px 10px 0;
}

.popover.top .arrow:after {
  bottom: -1px;
  left: -11px;
  border-top-color: rgba(0, 0, 0, 0.25);
  border-width: 11px 11px 0;
}

.popover.right .arrow {
  top: 50%;
  left: -10px;
  margin-top: -10px;
  border-right-color: #ffffff;
  border-width: 10px 10px 10px 0;
}

.popover.right .arrow:after {
  bottom: -11px;
  left: -1px;
  border-right-color: rgba(0, 0, 0, 0.25);
  border-width: 11px 11px 11px 0;
}

.popover.bottom .arrow {
  top: -10px;
  left: 50%;
  margin-left: -10px;
  border-bottom-color: #ffffff;
  border-width: 0 10px 10px;
}

.popover.bottom .arrow:after {
  top: -1px;
  left: -11px;
  border-bottom-color: rgba(0, 0, 0, 0.25);
  border-width: 0 11px 11px;
}

.popover.left .arrow {
  top: 50%;
  right: -10px;
  margin-top: -10px;
  border-left-color: #ffffff;
  border-width: 10px 0 10px 10px;
}

.popover.left .arrow:after {
  right: -1px;
  bottom: -11px;
  border-left-color: rgba(0, 0, 0, 0.25);
  border-width: 11px 0 11px 11px;
}

.thumbnails {
  margin-left: -20px;
  list-style: none;
  *zoom: 1;
}

.thumbnails:before,
.thumbnails:after {
  display: table;
  line-height: 0;
  content: "";
}

.thumbnails:after {
  clear: both;
}

.row-fluid .thumbnails {
  margin-left: 0;
}

.thumbnails > li {
  float: left;
  margin-bottom: 20px;
  margin-left: 20px;
}

.thumbnail {
  display: block;
  padding: 4px;
  line-height: 20px;
  border: 1px solid #ddd;
  -webkit-border-radius: 4px;
     -moz-border-radius: 4px;
          border-radius: 4px;
  -webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.055);
     -moz-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.055);
          box-shadow: 0 1px 3px rgba(0, 0, 0, 0.055);
  -webkit-transition: all 0.2s ease-in-out;
     -moz-transition: all 0.2s ease-in-out;
       -o-transition: all 0.2s ease-in-out;
          transition: all 0.2s ease-in-out;
}

a.thumbnail:hover {
  border-color: #0088cc;
  -webkit-box-shadow: 0 1px 4px rgba(0, 105, 214, 0.25);
     -moz-box-shadow: 0 1px 4px rgba(0, 105, 214, 0.25);
          box-shadow: 0 1px 4px rgba(0, 105, 214, 0.25);
}

.thumbnail > img {
  display: block;
  max-width: 100%;
  margin-right: auto;
  margin-left: auto;
}

.thumbnail .caption {
  padding: 9px;
  color: #555555;
}

.label,
.badge {
  font-size: 11.844px;
  font-weight: bold;
  line-height: 14px;
  color: #ffffff;
  text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
  white-space: nowrap;
  vertical-align: baseline;
  background-color: #999999;
}

.label {
  padding: 1px 4px 2px;
  -webkit-border-radius: 3px;
     -moz-border-radius: 3px;
          border-radius: 3px;
}

.badge {
  padding: 1px 9px 2px;
  -webkit-border-radius: 9px;
     -moz-border-radius: 9px;
          border-radius: 9px;
}

a.label:hover,
a.badge:hover {
  color: #ffffff;
  text-decoration: none;
  cursor: pointer;
}

.label-important,
.badge-important {
  background-color: #b94a48;
}

.label-important[href],
.badge-important[href] {
  background-color: #953b39;
}

.label-warning,
.badge-warning {
  background-color: #f89406;
}

.label-warning[href],
.badge-warning[href] {
  background-color: #c67605;
}

.label-success,
.badge-success {
  background-color: #468847;
}

.label-success[href],
.badge-success[href] {
  background-color: #356635;
}

.label-info,
.badge-info {
  background-color: #3a87ad;
}

.label-info[href],
.badge-info[href] {
  background-color: #2d6987;
}

.label-inverse,
.badge-inverse {
  background-color: #333333;
}

.label-inverse[href],
.badge-inverse[href] {
  background-color: #1a1a1a;
}

.btn .label,
.btn .badge {
  position: relative;
  top: -1px;
}

.btn-mini .label,
.btn-mini .badge {
  top: 0;
}

@-webkit-keyframes progress-bar-stripes {
  from {
    background-position: 40px 0;
  }
  to {
    background-position: 0 0;
  }
}

@-moz-keyframes progress-bar-stripes {
  from {
    background-position: 40px 0;
  }
  to {
    background-position: 0 0;
  }
}

@-ms-keyframes progress-bar-stripes {
  from {
    background-position: 40px 0;
  }
  to {
    background-position: 0 0;
  }
}

@-o-keyframes progress-bar-stripes {
  from {
    background-position: 0 0;
  }
  to {
    background-position: 40px 0;
  }
}

@keyframes progress-bar-stripes {
  from {
    background-position: 40px 0;
  }
  to {
    background-position: 0 0;
  }
}

.progress {
  height: 20px;
  margin-bottom: 20px;
  overflow: hidden;
  background-color: #f7f7f7;
  background-image: -moz-linear-gradient(top, #f5f5f5, #f9f9f9);
  background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#f5f5f5), to(#f9f9f9));
  background-image: -webkit-linear-gradient(top, #f5f5f5, #f9f9f9);
  background-image: -o-linear-gradient(top, #f5f5f5, #f9f9f9);
  background-image: linear-gradient(to bottom, #f5f5f5, #f9f9f9);
  background-repeat: repeat-x;
  -webkit-border-radius: 4px;
     -moz-border-radius: 4px;
          border-radius: 4px;
  filter: progid:dximagetransform.microsoft.gradient(startColorstr='#fff5f5f5', endColorstr='#fff9f9f9', GradientType=0);
  -webkit-box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
     -moz-box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
          box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
}

.progress .bar {
  float: left;
  width: 0;
  height: 100%;
  font-size: 12px;
  color: #ffffff;
  text-align: center;
  text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
  background-color: #0e90d2;
  background-image: -moz-linear-gradient(top, #149bdf, #0480be);
  background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#149bdf), to(#0480be));
  background-image: -webkit-linear-gradient(top, #149bdf, #0480be);
  background-image: -o-linear-gradient(top, #149bdf, #0480be);
  background-image: linear-gradient(to bottom, #149bdf, #0480be);
  background-repeat: repeat-x;
  filter: progid:dximagetransform.microsoft.gradient(startColorstr='#ff149bdf', endColorstr='#ff0480be', GradientType=0);
  -webkit-box-shadow: inset 0 -1px 0 rgba(0, 0, 0, 0.15);
     -moz-box-shadow: inset 0 -1px 0 rgba(0, 0, 0, 0.15);
          box-shadow: inset 0 -1px 0 rgba(0, 0, 0, 0.15);
  -webkit-box-sizing: border-box;
     -moz-box-sizing: border-box;
          box-sizing: border-box;
  -webkit-transition: width 0.6s ease;
     -moz-transition: width 0.6s ease;
       -o-transition: width 0.6s ease;
          transition: width 0.6s ease;
}

.progress .bar + .bar {
  -webkit-box-shadow: inset 1px 0 0 rgba(0, 0, 0, 0.15), inset 0 -1px 0 rgba(0, 0, 0, 0.15);
     -moz-box-shadow: inset 1px 0 0 rgba(0, 0, 0, 0.15), inset 0 -1px 0 rgba(0, 0, 0, 0.15);
          box-shadow: inset 1px 0 0 rgba(0, 0, 0, 0.15), inset 0 -1px 0 rgba(0, 0, 0, 0.15);
}

.progress-striped .bar {
  background-color: #149bdf;
  background-image: -webkit-gradient(linear, 0 100%, 100% 0, color-stop(0.25, rgba(255, 255, 255, 0.15)), color-stop(0.25, transparent), color-stop(0.5, transparent), color-stop(0.5, rgba(255, 255, 255, 0.15)), color-stop(0.75, rgba(255, 255, 255, 0.15)), color-stop(0.75, transparent), to(transparent));
  background-image: -webkit-linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
  background-image: -moz-linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
  background-image: -o-linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
  background-image: linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
  -webkit-background-size: 40px 40px;
     -moz-background-size: 40px 40px;
       -o-background-size: 40px 40px;
          background-size: 40px 40px;
}

.progress.active .bar {
  -webkit-animation: progress-bar-stripes 2s linear infinite;
     -moz-animation: progress-bar-stripes 2s linear infinite;
      -ms-animation: progress-bar-stripes 2s linear infinite;
       -o-animation: progress-bar-stripes 2s linear infinite;
          animation: progress-bar-stripes 2s linear infinite;
}

.progress-danger .bar,
.progress .bar-danger {
  background-color: #dd514c;
  background-image: -moz-linear-gradient(top, #ee5f5b, #c43c35);
  background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#ee5f5b), to(#c43c35));
  background-image: -webkit-linear-gradient(top, #ee5f5b, #c43c35);
  background-image: -o-linear-gradient(top, #ee5f5b, #c43c35);
  background-image: linear-gradient(to bottom, #ee5f5b, #c43c35);
  background-repeat: repeat-x;
  filter: progid:dximagetransform.microsoft.gradient(startColorstr='#ffee5f5b', endColorstr='#ffc43c35', GradientType=0);
}

.progress-danger.progress-striped .bar,
.progress-striped .bar-danger {
  background-color: #ee5f5b;
  background-image: -webkit-gradient(linear, 0 100%, 100% 0, color-stop(0.25, rgba(255, 255, 255, 0.15)), color-stop(0.25, transparent), color-stop(0.5, transparent), color-stop(0.5, rgba(255, 255, 255, 0.15)), color-stop(0.75, rgba(255, 255, 255, 0.15)), color-stop(0.75, transparent), to(transparent));
  background-image: -webkit-linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
  background-image: -moz-linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
  background-image: -o-linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
  background-image: linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
}

.progress-success .bar,
.progress .bar-success {
  background-color: #5eb95e;
  background-image: -moz-linear-gradient(top, #62c462, #57a957);
  background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#62c462), to(#57a957));
  background-image: -webkit-linear-gradient(top, #62c462, #57a957);
  background-image: -o-linear-gradient(top, #62c462, #57a957);
  background-image: linear-gradient(to bottom, #62c462, #57a957);
  background-repeat: repeat-x;
  filter: progid:dximagetransform.microsoft.gradient(startColorstr='#ff62c462', endColorstr='#ff57a957', GradientType=0);
}

.progress-success.progress-striped .bar,
.progress-striped .bar-success {
  background-color: #62c462;
  background-image: -webkit-gradient(linear, 0 100%, 100% 0, color-stop(0.25, rgba(255, 255, 255, 0.15)), color-stop(0.25, transparent), color-stop(0.5, transparent), color-stop(0.5, rgba(255, 255, 255, 0.15)), color-stop(0.75, rgba(255, 255, 255, 0.15)), color-stop(0.75, transparent), to(transparent));
  background-image: -webkit-linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
  background-image: -moz-linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
  background-image: -o-linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
  background-image: linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
}

.progress-info .bar,
.progress .bar-info {
  background-color: #4bb1cf;
  background-image: -moz-linear-gradient(top, #5bc0de, #339bb9);
  background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#5bc0de), to(#339bb9));
  background-image: -webkit-linear-gradient(top, #5bc0de, #339bb9);
  background-image: -o-linear-gradient(top, #5bc0de, #339bb9);
  background-image: linear-gradient(to bottom, #5bc0de, #339bb9);
  background-repeat: repeat-x;
  filter: progid:dximagetransform.microsoft.gradient(startColorstr='#ff5bc0de', endColorstr='#ff339bb9', GradientType=0);
}

.progress-info.progress-striped .bar,
.progress-striped .bar-info {
  background-color: #5bc0de;
  background-image: -webkit-gradient(linear, 0 100%, 100% 0, color-stop(0.25, rgba(255, 255, 255, 0.15)), color-stop(0.25, transparent), color-stop(0.5, transparent), color-stop(0.5, rgba(255, 255, 255, 0.15)), color-stop(0.75, rgba(255, 255, 255, 0.15)), color-stop(0.75, transparent), to(transparent));
  background-image: -webkit-linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
  background-image: -moz-linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
  background-image: -o-linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
  background-image: linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
}

.progress-warning .bar,
.progress .bar-warning {
  background-color: #faa732;
  background-image: -moz-linear-gradient(top, #fbb450, #f89406);
  background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#fbb450), to(#f89406));
  background-image: -webkit-linear-gradient(top, #fbb450, #f89406);
  background-image: -o-linear-gradient(top, #fbb450, #f89406);
  background-image: linear-gradient(to bottom, #fbb450, #f89406);
  background-repeat: repeat-x;
  filter: progid:dximagetransform.microsoft.gradient(startColorstr='#fffbb450', endColorstr='#fff89406', GradientType=0);
}

.progress-warning.progress-striped .bar,
.progress-striped .bar-warning {
  background-color: #fbb450;
  background-image: -webkit-gradient(linear, 0 100%, 100% 0, color-stop(0.25, rgba(255, 255, 255, 0.15)), color-stop(0.25, transparent), color-stop(0.5, transparent), color-stop(0.5, rgba(255, 255, 255, 0.15)), color-stop(0.75, rgba(255, 255, 255, 0.15)), color-stop(0.75, transparent), to(transparent));
  background-image: -webkit-linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
  background-image: -moz-linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
  background-image: -o-linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
  background-image: linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
}

.accordion {
  margin-bottom: 20px;
}

.accordion-group {
  margin-bottom: 2px;
  border: 1px solid #e5e5e5;
  -webkit-border-radius: 4px;
     -moz-border-radius: 4px;
          border-radius: 4px;
}

.accordion-heading {
  border-bottom: 0;
}

.accordion-heading .accordion-toggle {
  display: block;
  padding: 8px 15px;
}

.accordion-toggle {
  cursor: pointer;
}

.accordion-inner {
  padding: 9px 15px;
  border-top: 1px solid #e5e5e5;
}

.carousel {
  position: relative;
  margin-bottom: 20px;
  line-height: 1;
}

.carousel-inner {
  position: relative;
  width: 100%;
  overflow: hidden;
}

.carousel .item {
  position: relative;
  display: none;
  -webkit-transition: 0.6s ease-in-out left;
     -moz-transition: 0.6s ease-in-out left;
       -o-transition: 0.6s ease-in-out left;
          transition: 0.6s ease-in-out left;
}

.carousel .item > img {
  display: block;
  line-height: 1;
}

.carousel .active,
.carousel .next,
.carousel .prev {
  display: block;
}

.carousel .active {
  left: 0;
}

.carousel .next,
.carousel .prev {
  position: absolute;
  top: 0;
  width: 100%;
}

.carousel .next {
  left: 100%;
}

.carousel .prev {
  left: -100%;
}

.carousel .next.left,
.carousel .prev.right {
  left: 0;
}

.carousel .active.left {
  left: -100%;
}

.carousel .active.right {
  left: 100%;
}

.carousel-control {
  position: absolute;
  top: 40%;
  left: 15px;
  width: 40px;
  height: 40px;
  margin-top: -20px;
  font-size: 60px;
  font-weight: 100;
  line-height: 30px;
  color: #ffffff;
  text-align: center;
  background: #222222;
  border: 3px solid #ffffff;
  -webkit-border-radius: 23px;
     -moz-border-radius: 23px;
          border-radius: 23px;
  opacity: 0.5;
  filter: alpha(opacity=50);
}

.carousel-control.right {
  right: 15px;
  left: auto;
}

.carousel-control:hover {
  color: #ffffff;
  text-decoration: none;
  opacity: 0.9;
  filter: alpha(opacity=90);
}

.carousel-caption {
  position: absolute;
  right: 0;
  bottom: 0;
  left: 0;
  padding: 15px;
  background: #333333;
  background: rgba(0, 0, 0, 0.75);
}

.carousel-caption h4,
.carousel-caption p {
  line-height: 20px;
  color: #ffffff;
}

.carousel-caption h4 {
  margin: 0 0 5px;
}

.carousel-caption p {
  margin-bottom: 0;
}

.hero-unit {
  padding: 60px;
  margin-bottom: 30px;
  background-color: #eeeeee;
  -webkit-border-radius: 6px;
     -moz-border-radius: 6px;
          border-radius: 6px;
}

.hero-unit h1 {
  margin-bottom: 0;
  font-size: 60px;
  line-height: 1;
  letter-spacing: -1px;
  color: inherit;
}

.hero-unit p {
  font-size: 18px;
  font-weight: 200;
  line-height: 30px;
  color: inherit;
}

.pull-right {
  float: right;
}

.pull-left {
  float: left;
}

.hide {
  display: none;
}

.show {
  display: block;
}

.invisible {
  visibility: hidden;
}

.affix {
  position: fixed;
}


</style>
<link rel="stylesheet" title="D&eacute;sactiv&eacute; pour l'Aper&ccedil;u dans le navigateur : ../assets/css/bootstrap-responsive.css">
<style type="text/css">

/*!
 * Bootstrap Responsive v2.1.1
 *
 * Copyright 2012 Twitter, Inc
 * Licensed under the Apache License v2.0
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Designed and built with all the love in the world @twitter by @mdo and @fat.
 */

.clearfix {
  *zoom: 1;
}

.clearfix:before,
.clearfix:after {
  display: table;
  line-height: 0;
  content: "";
}

.clearfix:after {
  clear: both;
}

.hide-text {
  font: 0/0 a;
  color: transparent;
  text-shadow: none;
  background-color: transparent;
  border: 0;
}

.input-block-level {
  display: block;
  width: 100%;
  min-height: 30px;
  -webkit-box-sizing: border-box;
     -moz-box-sizing: border-box;
          box-sizing: border-box;
}

.hidden {
  display: none;
  visibility: hidden;
}

.visible-phone {
  display: none !important;
}

.visible-tablet {
  display: none !important;
}

.hidden-desktop {
  display: none !important;
}

.visible-desktop {
  display: inherit !important;
}

@media (min-width: 768px) and (max-width: 979px) {
  .hidden-desktop {
    display: inherit !important;
  }
  .visible-desktop {
    display: none !important ;
  }
  .visible-tablet {
    display: inherit !important;
  }
  .hidden-tablet {
    display: none !important;
  }
}

@media (max-width: 767px) {
  .hidden-desktop {
    display: inherit !important;
  }
  .visible-desktop {
    display: none !important;
  }
  .visible-phone {
    display: inherit !important;
  }
  .hidden-phone {
    display: none !important;
  }
}

@media (min-width: 1200px) {
  .row {
    margin-left: -30px;
    *zoom: 1;
  }
  .row:before,
  .row:after {
    display: table;
    line-height: 0;
    content: "";
  }
  .row:after {
    clear: both;
  }
  [class*="span"] {
    float: left;
    min-height: 1px;
    margin-left: 30px;
  }
  .container,
  .navbar-static-top .container,
  .navbar-fixed-top .container,
  .navbar-fixed-bottom .container {
    width: 1170px;
  }
  .span12 {
    width: 1170px;
  }
  .span11 {
    width: 1070px;
  }
  .span10 {
    width: 970px;
  }
  .span9 {
    width: 870px;
  }
  .span8 {
    width: 770px;
  }
  .span7 {
    width: 670px;
  }
  .span6 {
    width: 570px;
  }
  .span5 {
    width: 470px;
  }
  .span4 {
    width: 370px;
  }
  .span3 {
    width: 270px;
  }
  .span2 {
    width: 170px;
  }
  .span1 {
    width: 70px;
  }
  .offset12 {
    margin-left: 1230px;
  }
  .offset11 {
    margin-left: 1130px;
  }
  .offset10 {
    margin-left: 1030px;
  }
  .offset9 {
    margin-left: 930px;
  }
  .offset8 {
    margin-left: 830px;
  }
  .offset7 {
    margin-left: 730px;
  }
  .offset6 {
    margin-left: 630px;
  }
  .offset5 {
    margin-left: 530px;
  }
  .offset4 {
    margin-left: 430px;
  }
  .offset3 {
    margin-left: 330px;
  }
  .offset2 {
    margin-left: 230px;
  }
  .offset1 {
    margin-left: 130px;
  }
  .row-fluid {
    width: 100%;
    *zoom: 1;
  }
  .row-fluid:before,
  .row-fluid:after {
    display: table;
    line-height: 0;
    content: "";
  }
  .row-fluid:after {
    clear: both;
  }
  .row-fluid [class*="span"] {
    display: block;
    float: left;
    width: 100%;
    min-height: 30px;
    margin-left: 2.564102564102564%;
    *margin-left: 2.5109110747408616%;
    -webkit-box-sizing: border-box;
       -moz-box-sizing: border-box;
            box-sizing: border-box;
  }
  .row-fluid [class*="span"]:first-child {
    margin-left: 0;
  }
  .row-fluid .span12 {
    width: 100%;
    *width: 99.94680851063829%;
  }
  .row-fluid .span11 {
    width: 91.45299145299145%;
    *width: 91.39979996362975%;
  }
  .row-fluid .span10 {
    width: 82.90598290598291%;
    *width: 82.8527914166212%;
  }
  .row-fluid .span9 {
    width: 74.35897435897436%;
    *width: 74.30578286961266%;
  }
  .row-fluid .span8 {
    width: 65.81196581196582%;
    *width: 65.75877432260411%;
  }
  .row-fluid .span7 {
    width: 57.26495726495726%;
    *width: 57.21176577559556%;
  }
  .row-fluid .span6 {
    width: 48.717948717948715%;
    *width: 48.664757228587014%;
  }
  .row-fluid .span5 {
    width: 40.17094017094017%;
    *width: 40.11774868157847%;
  }
  .row-fluid .span4 {
    width: 31.623931623931625%;
    *width: 31.570740134569924%;
  }
  .row-fluid .span3 {
    width: 23.076923076923077%;
    *width: 23.023731587561375%;
  }
  .row-fluid .span2 {
    width: 14.52991452991453%;
    *width: 14.476723040552828%;
  }
  .row-fluid .span1 {
    width: 5.982905982905983%;
    *width: 5.929714493544281%;
  }
  .row-fluid .offset12 {
    margin-left: 105.12820512820512%;
    *margin-left: 105.02182214948171%;
  }
  .row-fluid .offset12:first-child {
    margin-left: 102.56410256410257%;
    *margin-left: 102.45771958537915%;
  }
  .row-fluid .offset11 {
    margin-left: 96.58119658119658%;
    *margin-left: 96.47481360247316%;
  }
  .row-fluid .offset11:first-child {
    margin-left: 94.01709401709402%;
    *margin-left: 93.91071103837061%;
  }
  .row-fluid .offset10 {
    margin-left: 88.03418803418803%;
    *margin-left: 87.92780505546462%;
  }
  .row-fluid .offset10:first-child {
    margin-left: 85.47008547008548%;
    *margin-left: 85.36370249136206%;
  }
  .row-fluid .offset9 {
    margin-left: 79.48717948717949%;
    *margin-left: 79.38079650845607%;
  }
  .row-fluid .offset9:first-child {
    margin-left: 76.92307692307693%;
    *margin-left: 76.81669394435352%;
  }
  .row-fluid .offset8 {
    margin-left: 70.94017094017094%;
    *margin-left: 70.83378796144753%;
  }
  .row-fluid .offset8:first-child {
    margin-left: 68.37606837606839%;
    *margin-left: 68.26968539734497%;
  }
  .row-fluid .offset7 {
    margin-left: 62.393162393162385%;
    *margin-left: 62.28677941443899%;
  }
  .row-fluid .offset7:first-child {
    margin-left: 59.82905982905982%;
    *margin-left: 59.72267685033642%;
  }
  .row-fluid .offset6 {
    margin-left: 53.84615384615384%;
    *margin-left: 53.739770867430444%;
  }
  .row-fluid .offset6:first-child {
    margin-left: 51.28205128205128%;
    *margin-left: 51.175668303327875%;
  }
  .row-fluid .offset5 {
    margin-left: 45.299145299145295%;
    *margin-left: 45.1927623204219%;
  }
  .row-fluid .offset5:first-child {
    margin-left: 42.73504273504273%;
    *margin-left: 42.62865975631933%;
  }
  .row-fluid .offset4 {
    margin-left: 36.75213675213675%;
    *margin-left: 36.645753773413354%;
  }
  .row-fluid .offset4:first-child {
    margin-left: 34.18803418803419%;
    *margin-left: 34.081651209310785%;
  }
  .row-fluid .offset3 {
    margin-left: 28.205128205128204%;
    *margin-left: 28.0987452264048%;
  }
  .row-fluid .offset3:first-child {
    margin-left: 25.641025641025642%;
    *margin-left: 25.53464266230224%;
  }
  .row-fluid .offset2 {
    margin-left: 19.65811965811966%;
    *margin-left: 19.551736679396257%;
  }
  .row-fluid .offset2:first-child {
    margin-left: 17.094017094017094%;
    *margin-left: 16.98763411529369%;
  }
  .row-fluid .offset1 {
    margin-left: 11.11111111111111%;
    *margin-left: 11.004728132387708%;
  }
  .row-fluid .offset1:first-child {
    margin-left: 8.547008547008547%;
    *margin-left: 8.440625568285142%;
  }
  input,
  textarea,
  .uneditable-input {
    margin-left: 0;
  }
  .controls-row [class*="span"] + [class*="span"] {
    margin-left: 30px;
  }
  input.span12,
  textarea.span12,
  .uneditable-input.span12 {
    width: 1156px;
  }
  input.span11,
  textarea.span11,
  .uneditable-input.span11 {
    width: 1056px;
  }
  input.span10,
  textarea.span10,
  .uneditable-input.span10 {
    width: 956px;
  }
  input.span9,
  textarea.span9,
  .uneditable-input.span9 {
    width: 856px;
  }
  input.span8,
  textarea.span8,
  .uneditable-input.span8 {
    width: 756px;
  }
  input.span7,
  textarea.span7,
  .uneditable-input.span7 {
    width: 656px;
  }
  input.span6,
  textarea.span6,
  .uneditable-input.span6 {
    width: 556px;
  }
  input.span5,
  textarea.span5,
  .uneditable-input.span5 {
    width: 456px;
  }
  input.span4,
  textarea.span4,
  .uneditable-input.span4 {
    width: 356px;
  }
  input.span3,
  textarea.span3,
  .uneditable-input.span3 {
    width: 256px;
  }
  input.span2,
  textarea.span2,
  .uneditable-input.span2 {
    width: 156px;
  }
  input.span1,
  textarea.span1,
  .uneditable-input.span1 {
    width: 56px;
  }
  .thumbnails {
    margin-left: -30px;
  }
  .thumbnails > li {
    margin-left: 30px;
  }
  .row-fluid .thumbnails {
    margin-left: 0;
  }
}

@media (min-width: 768px) and (max-width: 979px) {
  .row {
    margin-left: -20px;
    *zoom: 1;
  }
  .row:before,
  .row:after {
    display: table;
    line-height: 0;
    content: "";
  }
  .row:after {
    clear: both;
  }
  [class*="span"] {
    float: left;
    min-height: 1px;
    margin-left: 20px;
  }
  .container,
  .navbar-static-top .container,
  .navbar-fixed-top .container,
  .navbar-fixed-bottom .container {
    width: 724px;
  }
  .span12 {
    width: 724px;
  }
  .span11 {
    width: 662px;
  }
  .span10 {
    width: 600px;
  }
  .span9 {
    width: 538px;
  }
  .span8 {
    width: 476px;
  }
  .span7 {
    width: 414px;
  }
  .span6 {
    width: 352px;
  }
  .span5 {
    width: 290px;
  }
  .span4 {
    width: 228px;
  }
  .span3 {
    width: 166px;
  }
  .span2 {
    width: 104px;
  }
  .span1 {
    width: 42px;
  }
  .offset12 {
    margin-left: 764px;
  }
  .offset11 {
    margin-left: 702px;
  }
  .offset10 {
    margin-left: 640px;
  }
  .offset9 {
    margin-left: 578px;
  }
  .offset8 {
    margin-left: 516px;
  }
  .offset7 {
    margin-left: 454px;
  }
  .offset6 {
    margin-left: 392px;
  }
  .offset5 {
    margin-left: 330px;
  }
  .offset4 {
    margin-left: 268px;
  }
  .offset3 {
    margin-left: 206px;
  }
  .offset2 {
    margin-left: 144px;
  }
  .offset1 {
    margin-left: 82px;
  }
  .row-fluid {
    width: 100%;
    *zoom: 1;
  }
  .row-fluid:before,
  .row-fluid:after {
    display: table;
    line-height: 0;
    content: "";
  }
  .row-fluid:after {
    clear: both;
  }
  .row-fluid [class*="span"] {
    display: block;
    float: left;
    width: 100%;
    min-height: 30px;
    margin-left: 2.7624309392265194%;
    *margin-left: 2.709239449864817%;
    -webkit-box-sizing: border-box;
       -moz-box-sizing: border-box;
            box-sizing: border-box;
  }
  .row-fluid [class*="span"]:first-child {
    margin-left: 0;
  }
  .row-fluid .span12 {
    width: 100%;
    *width: 99.94680851063829%;
  }
  .row-fluid .span11 {
    width: 91.43646408839778%;
    *width: 91.38327259903608%;
  }
  .row-fluid .span10 {
    width: 82.87292817679558%;
    *width: 82.81973668743387%;
  }
  .row-fluid .span9 {
    width: 74.30939226519337%;
    *width: 74.25620077583166%;
  }
  .row-fluid .span8 {
    width: 65.74585635359117%;
    *width: 65.69266486422946%;
  }
  .row-fluid .span7 {
    width: 57.18232044198895%;
    *width: 57.12912895262725%;
  }
  .row-fluid .span6 {
    width: 48.61878453038674%;
    *width: 48.56559304102504%;
  }
  .row-fluid .span5 {
    width: 40.05524861878453%;
    *width: 40.00205712942283%;
  }
  .row-fluid .span4 {
    width: 31.491712707182323%;
    *width: 31.43852121782062%;
  }
  .row-fluid .span3 {
    width: 22.92817679558011%;
    *width: 22.87498530621841%;
  }
  .row-fluid .span2 {
    width: 14.3646408839779%;
    *width: 14.311449394616199%;
  }
  .row-fluid .span1 {
    width: 5.801104972375691%;
    *width: 5.747913483013988%;
  }
  .row-fluid .offset12 {
    margin-left: 105.52486187845304%;
    *margin-left: 105.41847889972962%;
  }
  .row-fluid .offset12:first-child {
    margin-left: 102.76243093922652%;
    *margin-left: 102.6560479605031%;
  }
  .row-fluid .offset11 {
    margin-left: 96.96132596685082%;
    *margin-left: 96.8549429881274%;
  }
  .row-fluid .offset11:first-child {
    margin-left: 94.1988950276243%;
    *margin-left: 94.09251204890089%;
  }
  .row-fluid .offset10 {
    margin-left: 88.39779005524862%;
    *margin-left: 88.2914070765252%;
  }
  .row-fluid .offset10:first-child {
    margin-left: 85.6353591160221%;
    *margin-left: 85.52897613729868%;
  }
  .row-fluid .offset9 {
    margin-left: 79.8342541436464%;
    *margin-left: 79.72787116492299%;
  }
  .row-fluid .offset9:first-child {
    margin-left: 77.07182320441989%;
    *margin-left: 76.96544022569647%;
  }
  .row-fluid .offset8 {
    margin-left: 71.2707182320442%;
    *margin-left: 71.16433525332079%;
  }
  .row-fluid .offset8:first-child {
    margin-left: 68.50828729281768%;
    *margin-left: 68.40190431409427%;
  }
  .row-fluid .offset7 {
    margin-left: 62.70718232044199%;
    *margin-left: 62.600799341718584%;
  }
  .row-fluid .offset7:first-child {
    margin-left: 59.94475138121547%;
    *margin-left: 59.838368402492065%;
  }
  .row-fluid .offset6 {
    margin-left: 54.14364640883978%;
    *margin-left: 54.037263430116376%;
  }
  .row-fluid .offset6:first-child {
    margin-left: 51.38121546961326%;
    *margin-left: 51.27483249088986%;
  }
  .row-fluid .offset5 {
    margin-left: 45.58011049723757%;
    *margin-left: 45.47372751851417%;
  }
  .row-fluid .offset5:first-child {
    margin-left: 42.81767955801105%;
    *margin-left: 42.71129657928765%;
  }
  .row-fluid .offset4 {
    margin-left: 37.01657458563536%;
    *margin-left: 36.91019160691196%;
  }
  .row-fluid .offset4:first-child {
    margin-left: 34.25414364640884%;
    *margin-left: 34.14776066768544%;
  }
  .row-fluid .offset3 {
    margin-left: 28.45303867403315%;
    *margin-left: 28.346655695309746%;
  }
  .row-fluid .offset3:first-child {
    margin-left: 25.69060773480663%;
    *margin-left: 25.584224756083227%;
  }
  .row-fluid .offset2 {
    margin-left: 19.88950276243094%;
    *margin-left: 19.783119783707537%;
  }
  .row-fluid .offset2:first-child {
    margin-left: 17.12707182320442%;
    *margin-left: 17.02068884448102%;
  }
  .row-fluid .offset1 {
    margin-left: 11.32596685082873%;
    *margin-left: 11.219583872105325%;
  }
  .row-fluid .offset1:first-child {
    margin-left: 8.56353591160221%;
    *margin-left: 8.457152932878806%;
  }
  input,
  textarea,
  .uneditable-input {
    margin-left: 0;
  }
  .controls-row [class*="span"] + [class*="span"] {
    margin-left: 20px;
  }
  input.span12,
  textarea.span12,
  .uneditable-input.span12 {
    width: 710px;
  }
  input.span11,
  textarea.span11,
  .uneditable-input.span11 {
    width: 648px;
  }
  input.span10,
  textarea.span10,
  .uneditable-input.span10 {
    width: 586px;
  }
  input.span9,
  textarea.span9,
  .uneditable-input.span9 {
    width: 524px;
  }
  input.span8,
  textarea.span8,
  .uneditable-input.span8 {
    width: 462px;
  }
  input.span7,
  textarea.span7,
  .uneditable-input.span7 {
    width: 400px;
  }
  input.span6,
  textarea.span6,
  .uneditable-input.span6 {
    width: 338px;
  }
  input.span5,
  textarea.span5,
  .uneditable-input.span5 {
    width: 276px;
  }
  input.span4,
  textarea.span4,
  .uneditable-input.span4 {
    width: 214px;
  }
  input.span3,
  textarea.span3,
  .uneditable-input.span3 {
    width: 152px;
  }
  input.span2,
  textarea.span2,
  .uneditable-input.span2 {
    width: 90px;
  }
  input.span1,
  textarea.span1,
  .uneditable-input.span1 {
    width: 28px;
  }
}

@media (max-width: 767px) {
  body {
    padding-right: 20px;
    padding-left: 20px;
  }
  .navbar-fixed-top,
  .navbar-fixed-bottom,
  .navbar-static-top {
    margin-right: -20px;
    margin-left: -20px;
  }
  .container-fluid {
    padding: 0;
  }
  .dl-horizontal dt {
    float: none;
    width: auto;
    clear: none;
    text-align: left;
  }
  .dl-horizontal dd {
    margin-left: 0;
  }
  .container {
    width: auto;
  }
  .row-fluid {
    width: 100%;
  }
  .row,
  .thumbnails {
    margin-left: 0;
  }
  .thumbnails > li {
    float: none;
    margin-left: 0;
  }
  [class*="span"],
  .row-fluid [class*="span"] {
    display: block;
    float: none;
    width: 100%;
    margin-left: 0;
    -webkit-box-sizing: border-box;
       -moz-box-sizing: border-box;
            box-sizing: border-box;
  }
  .span12,
  .row-fluid .span12 {
    width: 100%;
    -webkit-box-sizing: border-box;
       -moz-box-sizing: border-box;
            box-sizing: border-box;
  }
  .input-large,
  .input-xlarge,
  .input-xxlarge,
  input[class*="span"],
  select[class*="span"],
  textarea[class*="span"],
  .uneditable-input {
    display: block;
    width: 100%;
    min-height: 30px;
    -webkit-box-sizing: border-box;
       -moz-box-sizing: border-box;
            box-sizing: border-box;
  }
  .input-prepend input,
  .input-append input,
  .input-prepend input[class*="span"],
  .input-append input[class*="span"] {
    display: inline-block;
    width: auto;
  }
  .controls-row [class*="span"] + [class*="span"] {
    margin-left: 0;
  }
  .modal {
    position: fixed;
    top: 20px;
    right: 20px;
    left: 20px;
    width: auto;
    margin: 0;
  }
  .modal.fade.in {
    top: auto;
  }
}

@media (max-width: 480px) {
  .nav-collapse {
    -webkit-transform: translate3d(0, 0, 0);
  }
  .page-header h1 small {
    display: block;
    line-height: 20px;
  }
  input[type="checkbox"],
  input[type="radio"] {
    border: 1px solid #ccc;
  }
  .form-horizontal .control-label {
    float: none;
    width: auto;
    padding-top: 0;
    text-align: left;
  }
  .form-horizontal .controls {
    margin-left: 0;
  }
  .form-horizontal .control-list {
    padding-top: 0;
  }
  .form-horizontal .form-actions {
    padding-right: 10px;
    padding-left: 10px;
  }
  .modal {
    top: 10px;
    right: 10px;
    left: 10px;
  }
  .modal-header .close {
    padding: 10px;
    margin: -10px;
  }
  .carousel-caption {
    position: static;
  }
}

@media (max-width: 767px) {
  body {
    padding-top: 0;
  }
  .navbar-fixed-top,
  .navbar-fixed-bottom {
    position: static;
  }
  .navbar-fixed-top {
    margin-bottom: 20px;
  }
  .navbar-fixed-bottom {
    margin-top: 20px;
  }
  .navbar-fixed-top .navbar-inner,
  .navbar-fixed-bottom .navbar-inner {
    padding: 5px;
  }
  .navbar .container {
    width: auto;
    padding: 0;
  }
  .navbar .brand {
    padding-right: 10px;
    padding-left: 10px;
    margin: 0 0 0 -5px;
  }
  .nav-collapse {
    clear: both;
  }
  .nav-collapse .nav {
    float: none;
    margin: 0 0 10px;
  }
  .nav-collapse .nav > li {
    float: none;
  }
  .nav-collapse .nav > li > a {
    margin-bottom: 2px;
  }
  .nav-collapse .nav > .divider-vertical {
    display: none;
  }
  .nav-collapse .nav .nav-header {
    color: #777777;
    text-shadow: none;
  }
  .nav-collapse .nav > li > a,
  .nav-collapse .dropdown-menu a {
    padding: 9px 15px;
    font-weight: bold;
    color: #777777;
    -webkit-border-radius: 3px;
       -moz-border-radius: 3px;
            border-radius: 3px;
  }
  .nav-collapse .btn {
    padding: 4px 10px 4px;
    font-weight: normal;
    -webkit-border-radius: 4px;
       -moz-border-radius: 4px;
            border-radius: 4px;
  }
  .nav-collapse .dropdown-menu li + li a {
    margin-bottom: 2px;
  }
  .nav-collapse .nav > li > a:hover,
  .nav-collapse .dropdown-menu a:hover {
    background-color: #f2f2f2;
  }
  .navbar-inverse .nav-collapse .nav > li > a:hover,
  .navbar-inverse .nav-collapse .dropdown-menu a:hover {
    background-color: #111111;
  }
  .nav-collapse.in .btn-group {
    padding: 0;
    margin-top: 5px;
  }
  .nav-collapse .dropdown-menu {
    position: static;
    top: auto;
    left: auto;
    display: block;
    float: none;
    max-width: none;
    padding: 0;
    margin: 0 15px;
    background-color: transparent;
    border: none;
    -webkit-border-radius: 0;
       -moz-border-radius: 0;
            border-radius: 0;
    -webkit-box-shadow: none;
       -moz-box-shadow: none;
            box-shadow: none;
  }
  .nav-collapse .dropdown-menu:before,
  .nav-collapse .dropdown-menu:after {
    display: none;
  }
  .nav-collapse .dropdown-menu .divider {
    display: none;
  }
  .nav-collapse .nav > li > .dropdown-menu:before,
  .nav-collapse .nav > li > .dropdown-menu:after {
    display: none;
  }
  .nav-collapse .navbar-form,
  .nav-collapse .navbar-search {
    float: none;
    padding: 10px 15px;
    margin: 10px 0;
    border-top: 1px solid #f2f2f2;
    border-bottom: 1px solid #f2f2f2;
    -webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.1), 0 1px 0 rgba(255, 255, 255, 0.1);
       -moz-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.1), 0 1px 0 rgba(255, 255, 255, 0.1);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.1), 0 1px 0 rgba(255, 255, 255, 0.1);
  }
  .navbar-inverse .nav-collapse .navbar-form,
  .navbar-inverse .nav-collapse .navbar-search {
    border-top-color: #111111;
    border-bottom-color: #111111;
  }
  .navbar .nav-collapse .nav.pull-right {
    float: none;
    margin-left: 0;
  }
  .nav-collapse,
  .nav-collapse.collapse {
    height: 0;
    overflow: hidden;
  }
  .navbar .btn-navbar {
    display: block;
  }
  .navbar-static .navbar-inner {
    padding-right: 10px;
    padding-left: 10px;
  }
}

@media (min-width: 767px) {
  .nav-collapse.collapse {
    height: auto !important;
    overflow: visible !important;
  }
}


</style>
<link rel="stylesheet" title="D&eacute;sactiv&eacute; pour l'Aper&ccedil;u dans le navigateur : ../assets/css/GenerationCity.css" type="text/css">
<style type="text/css">

/* Add additional stylesheets below
-------------------------------------------------- */

/* BODY AND STRUCTURE
-------------------------------------------------- */
body {
	background-color: #e8e8e8;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 0.9em;
	padding-top: 108px;
	position: relative;
}

p {
	text-align: justify;
}

a {
	z-index: 10 !important;
}

.corps-page {
	background: #cdcdcd; /* Old browsers */
	/* IE9 SVG, needs conditional override of 'filter' to 'none' */

	background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIxMDAlIiB5Mj0iMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2NkY2RjZCIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjUwJSIgc3RvcC1jb2xvcj0iI2ZmZmZmZiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNjZGNkY2QiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
	background: -moz-linear-gradient(left, #cdcdcd 0%, #ffffff 50%, #cdcdcd 100%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, right top, color-stop(0%, #cdcdcd), color-stop(50%, #ffffff), color-stop(100%, #cdcdcd)); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(left, #cdcdcd 0%, #ffffff 50%, #cdcdcd 100%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(left, #cdcdcd 0%, #ffffff 50%, #cdcdcd 100%); /* Opera 11.10+ */
	background: -ms-linear-gradient(left, #cdcdcd 0%, #ffffff 50%, #cdcdcd 100%); /* IE10+ */
	background: linear-gradient(to right, #cdcdcd 0%, #ffffff 50%, #cdcdcd 100%); /* W3C */
	border-top-width: 0px;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-left-width: 1px;
	border-style: solid;
	border-color: #000000;
}
.pull-center {
	text-align: center;
}
.anchor {
	position: relative;
	margin-top: -95px;
	padding-top: 100px;
}
hr {
	margin-top : 0px;
	margin-bottom : 10px;
	border-top-width: 0px;
	border-bottom-width: 1px;
	border-bottom-style: solid;
	border-bottom-color: #929090;
}
.well {
	background: #dddddd;
	border-radius: 0px;
	border: none;
	margin-bottom: 5px;
	padding-top: 10px;
	padding-bottom: 1px;
	line-height: 18px;
}
.thumb {
	text-align: center;
	padding: 49px 10px 0px 10px;
	margin-bottom: -10px;
}
.thumb-drapeau {
	width: 50px;
	float: left;
	margin-right: 10px;
}
.thumb p {
	text-align: center;
}

.communique .thumb {
	padding: 0px 10px 0px 10px;
	margin-bottom: -10px;
}

.thumb-ville {
	margin-top: 51px;
}

h3 {
	font-size: 24px;
	color: #375c08;
	font-weight: lighter;
	font-stretch: narrower;
	margin-left: 18px;
	margin-top: 22px;
	line-height: 18px;
}
.listes h3 {
	color: #000000;
}
small {
	color: #375c08;
	margin: 5px;
	padding: 5px;
	font-weight: lighter;
	font-stretch: narrower;
}
ul {
	position: relative;
	z-index: 10 !important;
}
.align-left, .align-left p {
	text-align: left;
}
.accordion {
	position: relative;
	z-index: 1 !important;
}
div.span5.align-left > p {
	text-align: left;
}

/* ICONES
-------------------------------------------------- */


/* Icones menu*/

.icon-accueil, .icon-carte, .icon-pays, .icon-institut, .icon-evenement, .icon-participer, .icon-generation_city {
	margin: auto;
	background-position: center center;
	width: 50px;
	height: 50px;
	padding-bottom: 0px;
	position: relative;
	bottom: -15px;
	margin-bottom: 0px;
	margin-top: 20px;
}
.icon-accueil {
	background-image: url("../assets/img/IconesBDD/50/Accueil.png");
}
.icon-carte {
	background-image: url("../assets/img/IconesBDD/50/carte.png");
}
.icon-pays {
	background-image: url("../assets/img/IconesBDD/50/Pays1.png");
}
.icon-institut {
	background-image: url("../assets/img/IconesBDD/50/ocgc.png");
}
.icon-evenement {
	background-image: url("../assets/img/IconesBDD/50/evenement.png");
}
.icon-participer {
	background-image: url("../assets/img/IconesBDD/50/participer.png");
}
.icon-generation_city {
	background-image: url("../assets/img/IconesBDD/50/generationcity.png");
}
/* Petites Icones */

.icon-user {
	background-position: center center;
	background-image: url("../assets/img/Icones/User-small.png");
}
.icon-user-white {
	background-position: center center;
	background-image: url("../assets/img/Icones/User-small-white.png");
}
.icon-pays-small {
	background-position: center center;
	background-image: url("../assets/img/Icones/Pays-small.png");
}
.icon-pays-small-white {
	background-position: center center;
	background-image: url("../assets/img/Icones/Pays-small-white.png");
}
.icon-jugement {
	background-position: center center;
	background-image: url("../assets/img/Icones/jugement.png");
}
.icone-token img {
	width: 40px;
}
/* Formulaires
-------------------------------------------------- */

form {
	position: relative;
	z-index: 2 !important;
}
select, textarea, input[type="text"], input[type="password"], input[type="datetime"], input[type="datetime-local"], input[type="date"], input[type="month"], input[type="time"], input[type="week"], input[type="number"], input[type="email"], input[type="url"], input[type="search"], input[type="tel"], input[type="color"], .uneditable-input {
	background-color: #fcfac0;
	border-radius: 0px;
	border-width: 1px;
	border-color: #888888;
}
.navbar-form input {
	margin-bottom: 5px;
	font-size: 0.9em;
	line-height: normal;
	padding-top: 2px;
	padding-bottom: 2px;
}
.form-button-inline {
	display: inline;
}
/* Boutons
-------------------------------------------------- */

input[type="submit"], .btn {
	border-radius: 0px;
	font-size: 0.9em;
	position: relative;
	z-index: 10 !important;
}
.btn-large {
	font-size: 1.2em;
	font-weight: 100;
	margin-left: 4px;
	margin-right: 4px;
	margin-top: 10px;
	margin-bottom: 0px;
	padding-top: 5px;
	padding-bottom: 5px;
	padding-left: 20px;
	padding-right: 20px;
}
.btn-danger, .btn-primary, .btn-success, .btn-info {
	border-color: #ffffff #ffffff #ffffff #ffffff;
	border-color: rgba(250, 250, 2500, 1) rgba(250, 250, 2500, 1) rgba(250, 250, 2500, 1) rgba(250, 250, 2500, 1);
}
.btn-primary {
	color: #ffffff;
	text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
	background-color: #2c67eb;
 *background-color: #1c4297;
	background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#2c67eb), to(#1c4297));
	background-image: -webkit-linear-gradient(top, #2c67eb, #1c4297);
	background-image: -o-linear-gradient(top, #2c67eb, #1c4297);
	background-image: linear-gradient(to bottom, #2c67eb, #1c4297);
	background-image: -moz-linear-gradient(top, #2c67eb, #1c4297);
	background-repeat: repeat-x;
 filter: progid:dximagetransform.microsoft.gradient(startColorstr='#ff2c67eb', endColorstr='#ff1c4297', GradientType=0);
	-webkit-box-shadow: 0px 0px 2px 0px rgba(0, 0, 0, 0.3);
	box-shadow: 0px 0px 2px 0px rgba(0, 0, 0, 0.3);
}

input[type='submit'] {
	color: #ffffff;
	text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
	background-color: #2c67eb;
 *background-color: #1c4297;
	background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#2c67eb), to(#1c4297));
	background-image: -webkit-linear-gradient(top, #2c67eb, #1c4297);
	background-image: -o-linear-gradient(top, #2c67eb, #1c4297);
	background-image: linear-gradient(to bottom, #2c67eb, #1c4297);
	background-image: -moz-linear-gradient(top, #2c67eb, #1c4297);
	background-repeat: repeat-x;
 filter: progid:dximagetransform.microsoft.gradient(startColorstr='#ff2c67eb', endColorstr='#ff1c4297', GradientType=0);
	-webkit-box-shadow: 0px 0px 2px 0px rgba(0, 0, 0, 0.3);
	box-shadow: 0px 0px 2px 0px rgba(0, 0, 0, 0.3);
}

.btn-primary:hover, .btn-primary:active, .btn-primary.active, .btn-primary.disabled, .btn-primary[disabled] {
	color: #ffffff;
	background-color: #1c4297;
 *background-color: #2c67eb;
}
.btn-connexion {
	margin-left: 0px;
	margin-right: 0px;
	margin-top: 20px;
	margin-bottom: 4px;
	padding-top: 4px;
	padding-bottom: 4px;
	color: #325608;
	text-shadow: 0 -1px 0 rgba(250, 250, 250, 0.25);
	background-color: #ffd8c4;
 *background-color: #ffffff;
	background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#ffd8c4), to(#ffffff));
	background-image: -webkit-linear-gradient(top, #ffd8c4, #ffffff);
	background-image: -o-linear-gradient(top, #ffd8c4, #ffffff);
	background-image: linear-gradient(to bottom, #ffd8c4, #ffffff);
	background-image: -moz-linear-gradient(top, #ffd8c4, #ffffff);
	background-repeat: repeat-x;
	border-color: #ffffff #ffffff #ffffff;
	border-color: rgba(250, 250, 250, 0.1) rgba(250, 250, 250, 0.1) rgba(250, 250, 0, 0.25);
 filter: progid:dximagetransform.microsoft.gradient(startColorstr='#ffffff', endColorstr='#ffd8c4', GradientType=0);
}
.btn-connexion:hover, .btn-connexion:active, .btn-connexion.active, .btn-connexion.disabled, .btn-connexion[disabled] {
	color: #325608;
	background-color: #ffffff;
 *background-color: #ffd8c4;
}
.btn-margin-left {
	margin-left: 10px;
}
.btn-group > .btn:first-child, .btn-group > .btn:last-child, .btn-group > .dropdown-toggle, .btn-group > .btn.large:first-child, .btn-group > .btn.large:last-child, .btn-group > .large.dropdown-toggle {
	-webkit-border-radius: 0px;
	border-radius: 0px;
}
.btn-ssmarge {
	position:relative;
	margin-left: -4px;
	margin-top: -9px;
}

/* NAV TOP MENU
-------------------------------------------------- */

.navbar-inner {
	background-color: #6e9fff;
	background-image: url("../assets/img/FondNavbar.png");
 filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
}
.navbar .nav > li > a {
	background: #ffffff; /* Old browsers */
	background: -moz-linear-gradient(top, #ffffff 0%, #dfdfdf 100%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #ffffff), color-stop(100%, #dfdfdf)); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(top, #ffffff 0%, #dfdfdf 100%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(top, #ffffff 0%, #dfdfdf 100%); /* Opera 11.10+ */
	background: -ms-linear-gradient(top, #ffffff 0%, #dfdfdf 100%); /* IE10+ */
	background: linear-gradient(to bottom, #ffffff 0%, #dfdfdf 100%); /* W3C */
 filter: progid:dximagetransform.microsoft.gradient(startColorstr='#ffffff', endColorstr='#dfdfdf', GradientType=0);
	-webkit-box-shadow: -1px -1px 2px 0px rgba(0, 0, 0, 0.3);
	box-shadow: -1px -1px 2px 0px rgba(0, 0, 0, 0.3);
	padding-top: 5px;
	padding-right: 10px;
	padding-bottom: 5px;
	padding-left: 10px;
	color: #325608;
	text-shadow: #ffffff;
	font-size: 0.9em;
	margin-top: 5px;
	margin-right: 4px;
	margin-bottom: 6px;
	margin-left: 4px;
}
.navbar .nav > li > a:hover {
 filter: progid:dximagetransform.microsoft.gradient(startColorstr='#ffffff', endColorstr='#dfdfdf', GradientType=0);
}
body > .navbar .brand {
	padding-right: 0;
	padding-left: 0;
	margin-right: 19px;
	font-weight: 150;
	color: #ffffff;
	margin-top: 70px;
	text-shadow: 1px 1px 1px rgba(0,0,0,.6), 0 0 2px rgba(0,0,0,.2);
	-webkit-transition: all .2s linear;
	-moz-transition: all .2s linear;
	transition: all .2s linear;
}
/* logo monde GC */
body > .navbar .brand:hover {
	text-decoration: none;
	text-shadow: 0 1px 0 rgba(0,0,0,.1), 0 0 4px rgba(0,0,0,.125);
}
/* formulaire de connection */
.navbar-form {
	float: right;
	text-align: right;
}
.navbar form {
	margin-bottom: -1px;
	margin-left: 10px;
	margin-top: -1px;
}

.offset{
	margin-top: 5px;
	}


/* Pseudo en blanc */
.Nav-pseudo {
	color: #ffffff;
	font-size: 0.8em;
	position: relative;
	top: 4px;
	text-shadow: 2px 0px 2px rgba(0, 0, 0, 0.3), 2px 0px 6px rgba(0, 0, 0, 0.1);
}
/* Btn du menu gestion */
.btn-nav-gestion {
	float: right;
	margin-top: 10px;
	padding-top: 4px;
	padding-bottom: 4px;
}

/* mot bienvenue devant pseudo. S'affiche pour les �crans sup a 1200px */
.bienvenue {
	visibility:hidden;
}


/* NavBar Haut-Conseil
-------------------------------------------------- */
.navbar-haut-conseil div {
	display: inline;
	margin-right: -4px;
}
.navbar-haut-conseil > a.btn.btn-primary {
	margin-right: -4px;
}

/* Boutons moderation
-------------------------------------------------- */

.en-tete {
	position:relative;
	bottom : 50Px;
	margin-bottom: -50px;
	}


/* Dropdown
-------------------------------------------------- */

.dropdown-menu a {
	font-size: 0.85em;
}
/* Surlignage */
.dropdown-menu li > a:hover, .dropdown-menu li > a:focus, .dropdown-submenu:hover > a {
	color: #ffffff;
	background-color: #1c4297;
	background-image: none;
	background-repeat: repeat-x;
}
/* Dropdown Double 
-------------------------------------------------- */

.dropdown-double {
	width: 580px;
	font-weight: 0.7em;
	height: 600px;
	overflow: auto;
}
.drop-colonne-gauche {
	float: left;
	width: 200px;
}
.drop-colonne-droite {
	float: right;
	width: 200px;
	position: relative;
	left: -80px;
}
ul.dropdown-menu.dropdown-double > div.drop-colonne-gauche > li.nav-header {
	width: 200px;
}
ul.dropdown-menu.dropdown-double > div.drop-colonne-gauche > li.divider {
	width: 200px;
}
ul.dropdown-menu.dropdown-double > div.drop-colonne-gauche > li, ul.dropdown-menu.dropdown-double > div.drop-colonne-gauche > li > a, ul.dropdown-menu.dropdown-double > div.drop-colonne-gauche > li > a:hover, ul.dropdown-menu.dropdown-double > div.drop-colonne-gauche > li > a:active, ul.dropdown-menu.dropdown-double > div.drop-colonne-gauche > li > a:link, ul.dropdown-menu.dropdown-double > div.drop-colonne-gauche > li > a:visited {
	width: 200px;
}
ul.dropdown-menu.dropdown-double > div.drop-colonne-droite > li.nav-header {
	width: 200px;
}
ul.dropdown-menu.dropdown-double > div.drop-colonne-droite > li.divider {
	width: 200px;
}
ul.dropdown-menu.dropdown-double > div.drop-colonne-droite > li, ul.dropdown-menu.dropdown-double > div.drop-colonne-droitee > li > a, ul.dropdown-menu.dropdown-double > div.drop-colonne-droite > li > a:hover, ul.dropdown-menu.dropdown-double > div.drop-colonne-droite > li > a:active, ul.dropdown-menu.dropdown-double > div.drop-colonne-droite > li > a:link, ul.dropdown-menu.dropdown-double > div.drop-colonne-droite > li > a:visited {
	width: 200px;
}
.img-menu-drapeau {
	width: 25px;
	height: 15px;
}
.img-continent {
	width: 30px;
}
/* HEADER
-------------------------------------------------- */

/* Jumbotron Header
-------------------------------------------------- */

.jumbotron {
	background-color: #6e9fff;
	background-position: bottom;
	background-size: cover;
	text-align: center;
	text-shadow: 2px 0px 2px rgba(0, 0, 0, 0.3), 2px 0px 6px rgba(0, 0, 0, 0.1);
	box-shadow: 0px 3px 7px rgba(0, 0, 0, 0.2) inset, 0px -3px 7px rgba(0, 0, 0, 0.2) inset;
	color: #ffffff;
}
.jumbotron .container {
	position: relative;
	z-index: 2;
	height: 320px;
	overflow: hidden;
}
.jumbotron .container-carousel {
	position: relative;
	margin-top: -5px;
	height: 450px;
}
.jumbotron-institut {
	background-color: transparent;
	background: #9babbf; /* Old browsers */
	/* IE9 SVG, needs conditional override of 'filter' to 'none' */
	background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iIzliYWJiZiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiM2YTcxODEiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
	background: -moz-linear-gradient(top, #9babbf 0%, #6a7181 100%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #9babbf), color-stop(100%, #6a7181)); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(top, #9babbf 0%, #6a7181 100%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(top, #9babbf 0%, #6a7181 100%); /* Opera 11.10+ */
	background: -ms-linear-gradient(top, #9babbf 0%, #6a7181 100%); /* IE10+ */
	background: linear-gradient(to bottom, #9babbf 0%, #6a7181 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#9babbf', endColorstr='#6a7181', GradientType=0 ); /* IE6-8 */
	background-position: bottom;
	background-size: cover;
	background-origin: content-box;
}
.jumbotron h1 {
	font-size: 2.5em;
	line-height: 1em;
}
.jumbotron p {
	font-size: 1.1em;
	line-height: 1em;
	text-align: center;
}

.header em a {
	color:#ffffff;
	text-decoration:underline;
}

.header {
	padding-top: 10px;
	margin-bottom: 50px;
}
.header p {
	margin-left: 160px;
	margin-right: 160px;
}
.Master-link p {
	font-size: 1.5em;
	text-shadow: 1px 1px 1px rgba(0,0,0,.6), 0 0 2px rgba(0,0,0,.2);
}
/* Bandeau Stat Acceuil
-------------------------------------------------- */


.bandeau-stat {
	background: #1c4399; /* Old browsers */
	background: #2c67ec; /* Old browsers */
	/* IE9 SVG, needs conditional override of 'filter' to 'none' */

	background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iIzJjNjdlYyIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiMxYzQzOTkiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
	background: -moz-linear-gradient(top, #2c67ec 0%, #1c4399 100%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #2c67ec), color-stop(100%, #1c4399)); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(top, #2c67ec 0%, #1c4399 100%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(top, #2c67ec 0%, #1c4399 100%); /* Opera 11.10+ */
	background: -ms-linear-gradient(top, #2c67ec 0%, #1c4399 100%); /* IE10+ */
	background: linear-gradient(to bottom, #2c67ec 0%, #1c4399 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#2c67ec', endColorstr='#1c4399', GradientType=0 ); /* IE6-9 */
	color: #ffffff;
	border-top-style: solid;
	border-width: 1px;
	border-color: #ffffff;
}
.bandeau-stat .container {
	background-image: url(../assets/img/fond-stat.png);
	background-position: left;
	background-repeat: no-repeat;
	border-top-width: 1px;
	border-bottom-width: 1px;
	padding-top: 2px;
	padding-bottom: 5px;
}
.bandeau-stat li {
	display: inline-block;
	margin-top: 28px;
	margin-left: 70px;
	margin-bottom: 10px;
	margin-right: 0px;
	text-shadow: 1px 1px 1px rgba(0,0,0,.6), 0 0 2px rgba(0,0,0,.2);
}
.bandeau-stat .label {
	font-size: 3em;
	text-shadow: 1px 1px 1px rgba(0,0,0,.6), 0 0 2px rgba(0,0,0,.2);
	margin-top: 10px;
	margin-bottom: 10px;
	padding-bottom: 0px;
	padding-top: 10px;
	background: #2c66ea; /* Old browsers */
	/* IE9 SVG, needs conditional override of 'filter' to 'none' */

	background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iIzJjNjZlYSIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiMxZjQ5YTciIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
	background: -moz-linear-gradient(top, #2c66ea 0%, #1f49a7 100%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #2c66ea), color-stop(100%, #1f49a7)); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(top, #2c66ea 0%, #1f49a7 100%); /* 		Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(top, #2c66ea 0%, #1f49a7 100%); /* Opera 11.10+ */
	background: -ms-linear-gradient(top, #2c66ea 0%, #1f49a7 100%); /* IE10+ */
	background: linear-gradient(to bottom, #2c66ea 0%, #1f49a7 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#2c66ea', endColorstr='#1f49a7', GradientType=0 ); /* IE6-9 */
	border-radius: 0px;
}
.live-stat {
	float: left;
	font-size: 0.9em;
	margin-top: 35px;
	margin-left: 12px;
}
/* Acces rapide acceuil
-------------------------------------------------- */

.liens-rapides {
	position: relative;
	text-align: center;
	color: #345908;
	font-size: 1em;
	margin-top: 10px;
	z-index: 10;
}
.liens-rapides p {
	text-align: center;
}
.liens-rapides a {
	color: #345908;
	font-weight: 600;
	text-decoration: underline;
}
/* Titre Vert h1
-------------------------------------------------- */

.titre-vert {
	text-align: center;
}
.titre-vert h1 {
	color: #FFFFFF;
	font-weight: 500;
	padding: 10px 5px;
	margin: 0px;
	text-shadow: 1px 1px 1px rgba(0,0,0,.6), 0 0 2px rgba(0,0,0,.2);
	background: #51910b; /* Old browsers */
	background: -moz-linear-gradient(top, #51910b 0%, #3b6509 100%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #51910b), color-stop(100%, #3b6509)); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(top, #51910b 0%, #3b6509 100%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(top, #51910b 0%, #3b6509 100%); /* Opera 11.10+ */
	background: -ms-linear-gradient(top, #51910b 0%, #3b6509 100%); /* IE10+ */
	background: linear-gradient(to bottom, #51910b 0%, #3b6509 100%); /* W3C */
 filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#51910b', endColorstr='#3b6509', GradientType=0 ); /* IE6-9 */
}
.titre-vert img {
	position: relative;
	top: 12px;
	margin-bottom: -12px;
}
/* Titre bleu h1
-------------------------------------------------- */

.titre-bleu {
	text-align: center;
}
.titre-bleu h1 {
	color: #FFFFFF;
	font-weight: 500;
	padding: 10px 5px;
	margin: 0px;
	text-shadow: 1px 1px 1px rgba(0,0,0,.6), 0 0 2px rgba(0,0,0,.2);
	background: #4172c1; /* Old browsers */
	background: -moz-linear-gradient(top, #4172c1 0%, #113e82 100%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #4172c1), color-stop(100%, #113e82)); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(top, #4172c1 0%, #113e82 100%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(top, #4172c1 0%, #113e82 100%); /* Opera 11.10+ */
	background: -ms-linear-gradient(top, #4172c1 0%, #113e82 100%); /* IE10+ */
	background: linear-gradient(to bottom, #4172c1 0%, #113e82 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#4172c1', endColorstr='#113e82', GradientType=0 ); /* IE6-8 */
}
.titre-bleu img {
	position: relative;
	top: 12px;
	margin-bottom: -12px;
}
/* Titre gris h3
-------------------------------------------------- */

.titre-gris {
	text-align: center;
}
.titre-gris h3 {
	color: #375c08;
	font-size: 1.3em;
	line-height: 1.1em;
	font-weight: lighter;
	padding: 10px 10px 10px 10px;
	margin: 0px 0px 0px 0px;
	background: #ffffff; /* Old browsers */
	background: -moz-linear-gradient(top, #ffffff 0%, #dfdfdf 100%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #ffffff), color-stop(100%, #dfdfdf)); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(top, #ffffff 0%, #dfdfdf 100%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(top, #ffffff 0%, #dfdfdf 100%); /* Opera 11.10+ */
	background: -ms-linear-gradient(top, #ffffff 0%, #dfdfdf 100%); /* IE10+ */
	background: linear-gradient(to bottom, #ffffff 0%, #dfdfdf 100%); /* W3C */
 filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#dfdfdf', GradientType=0 ); /* IE6-9 */
	-webkit-box-shadow: 1px 1px 2px 0px rgba(0, 0, 0, 0.3);
	box-shadow: 1px 1px 2px 0px rgba(0, 0, 0, 0.3);
	position: relative;
}
.titre-gris img {
	width: 70px;
	position: relative;
	top: 8px;
	margin-bottom: -8px;
	z-index: 3;
}
/* Notifications
-------------------------------------------------- */
.liste-transparente {
	list-style: none;
	margin: 0px;
	padding: 0px 10px 0px 10px;
	;
	z-index: 1;
}
.liste-transparente li {
	margin-top: 10px;
	margin-bottom: 0px;
	padding: 5px;
	position: relative;
}
.liste-transparente p {
	margin-right: 10px;
	margin-left: 10px;
}
.liste-transparente h4 {
	text-align: center;
}
.fond-notification {
	background-color: #dcdcdc;
}
.fond-notification small {
	line-height: 3em;
}
.fond-notification p {
	margin-top: -10px;
}
/* Listes
-------------------------------------------------- */

.listes {
	list-style: none;
	margin: 0px;
	padding: 0px 10px 0px 10px;
	;
	z-index: 1;
}
.listes li {
	background-color: #dcdcdc;
	margin-top: 10px;
	margin-bottom: 0px;
	padding: 5px;
	position: relative;
}
.info-listes {
	display: block;
	margin-left: 15px;
	margin-top: 0px;
}
.listes p {
	line-height: 1.2em;
	font-size: 0.9em;
	margin-bottom: 10px;
}
.listes h4 {
	font-size: 1.6em;
	line-height: 1.2em;
	margin-bottom: 0px;
}

.listes .btn {
	margin-top: 5px;
}

.listes small {
	color: #325508;
	margin: auto;
	padding: 0px;

}
.listes hr {
	margin-top: -1px;
}
.img-listes {
	padding: 5px;
}
.listes li .btn {
	margin-bottom: 10px;
}

.listes-visiteurs h4, .listes-visiteurs h5{
	margin-bottom: -10px;
}

.listes-visiteurs h5{
	margin-bottom: 0px;
}

.listes-visiteurs p{
		margin-top: 20px;
	margin-bottom: 20px;
}


/* Liste membre groupe en attente
-------------------------------------------------- */

.attente {
	background-color: #375c08;
	color: #375c08;
}
.attente .img-listes {
	border: solid;
	border-width: 5px;
	border-color: #375c08;
}
/* Liste token
-------------------------------------------------- */
.token {
	text-align: center;
	font-size: 18px;
	margin-left: 60px;
}
.token li p {
	text-align: center;
	background: #dfdfdf;
	margin: 0;
	margin-top: -15px;
	margin-right: -12px;
	margin-left: -12px;
	padding: -5px;
	padding-top: 10px;
	font-size:13px;
	font-weight:600;
}
/* footer
-------------------------------------------------- */
footer {
	margin-top: 10px;
	margin-bottom: 30px;
	position: relative;
}
footer ul {
	list-style: none;
	margin: 0px;
	padding: 0px;
	display: inline;
	font-size: 0.8em;
	line-height: 0.8em;
}
footer .liens-rapides {
	margin: 0px;
	padding: 0px;
	font-weight: 200;
}
footer li {
	display: inline;
	margin: 0px;
	margin-right: 10px;
	padding: 0px;
}
.copyright {
	position: relative;
	font-size: 0.8em;
	line-height: 0.8em;
	margin: 0px;
	margin-right: -0px;
	padding: 0px;
	text-align: left;
	float: right;
	display: block;
}
.copyright p {
	margin: 0px;
	margin-top: 5px;
	padding: 0px;
	color: #325508;
	display: block;
}
footer img {
	float: right;
	width: 100px;
	margin-top: -10px;
	display: block;
}
/* INFO GDE CARTE
------------------------- */
#info {
	position: relative;
	overflow: auto;
	text-align: left;
	height: 500px;
	background-color: #456dc5;
	line-height: 0.9em;
	margin: 0px;
	padding: 0px;
}
#map {
	position: relative;
}
.container-fluid {
	position: relative;
	padding: 0px 0px;
	margin-top: -5px;
}
#info  {
	margin: 0px;
	padding : 0px;
}
#info .fiche {
	background-color: #1d459d;
	padding-bottom: 10px;
}
.infocarte-icon img {
	height: 55px;
	margin-left: 10px;
	margin-bottom: 20px;
}
#info h1 {
	margin-left: 10px;
}
#info h3 {
	line-height: 1em;
	color: #FFFFFF;
	margin-bottom: 0px;
	margin-left: 10px;
	margin-top: 5px;
	line-height: 22px;
}
#info h4 {
	line-height: 0.9em;
	font-weight: lighter;
	margin-left: 10px;
	margin-bottom: 5px;
}
#info p {
	text-shadow: none;
	font-size: 0.90em;
	line-height: 1em;
	text-align: left;
	font-weight: lighter;
	margin-left: 10px;
	margin-right: 10px;
	margin-bottom: 5px;
}
#info a {
	color: #FFF;
	text-decoration: underline;
}
#info .illustration {
	height: 130px;
	overflow: hidden;
}
#info .illustration img {
	width: 100%;
}
#info .text {
	margin-top: 20px;
	font-size: 0.9em;
	line-height: 1.3em;
	font-style: italic;
}
#info .pied {
	text-align: center;
	color: #ffffff;
	text-decoration: none;
	padding: 10px;
}
#info .pied .btn {
	text-align: center;
	color: #ffffff;
	text-decoration: none;
}
#map label {
	position: relative;
	bottom: -3px;
	display: inline;
	margin-bottom: 0px;
}
#info .pop-up-ville {
	width: 425px;
}
.small-map {
	margin-top: 15px;
	padding-top: 20px;
}
.stat-continent {
	background-color: #1d459d;
	color: #FFFFFF;
	padding: 5px;
	text-align: center;
}
.stat-continent p {
	text-align: center;
	margin-top: 8px;
}
.stat-continent .stat {
	margin-left: 20px;
	margin-right: 20px;
}
.stat-continent .chiffre {
	font-size: 1.3em;
}
.liste-pays h3 {
	position: relative;
	left : -25px;
	top: -20px;
	margin-bottom: -20px;
	font-size: 20px;
	line-height: 30px;
}
/* Sidenav for Docs
-------------------------------------------------- */

.bs-docs-sidenav {
	width: 228px;
	background : #456dc6;
	margin: -116px 0 0;
	padding: 0;
	text-align: left;
}
.bs-docs-sidenav > li > a {
	display: block;
 *width: 190px;
	background-color: #1d459e;
	margin: 0 0 -1px;
	padding: 8px 14px;
	font-size: 15px;
	text-decoration: none;
}
.bs-docs-sidenav > li > a {
	display: block;
 *width: 190px;
	background-color: #1d459e;
	color: #ffffff;
	margin: 0 0 -1px;
	padding: 8px 14px;
	font-size: 15px;
	text-decoration: none;
}
.bs-docs-sidenav p {
	text-align: left;
}
.bs-docs-sidenav > li > a:hover {
	background-color: #456dc6;
}
.bs-docs-sidenav > li:first-child > a {
	background-color: #456dc6;
	height: 100px;
}
.bs-docs-sidenav > li > a > img {
	width: 75px;
	max-height: 75px;
	float: left;
	margin-right: 10px;
}
.bs-docs-sidenav > .active > a {
	position: relative;
	z-index: 3;
	background: #ffffff; /* Old browsers */
	color: #1d459e;
	background: #ffffff; /* Old browsers */
	/* IE9 SVG, needs conditional override of 'filter' to 'none' */

	background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2ZmZmZmZiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNkY2RjZGMiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
	background: -moz-linear-gradient(top, #ffffff 0%, #dcdcdc 100%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #ffffff), color-stop(100%, #dcdcdc)); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(top, #ffffff 0%, #dcdcdc 100%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(top, #ffffff 0%, #dcdcdc 100%); /* Opera 11.10+ */
	background: -ms-linear-gradient(top, #ffffff 0%, #dcdcdc 100%); /* IE10+ */
	background: linear-gradient(to bottom, #ffffff 0%, #dcdcdc 100%); /* W3C */
 filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#dcdcdc', GradientType=0 ); /* IE6-8 */
}
.bs-docs-sidenav.affix {
	top: 250px;
}
.bs-docs-sidenav.affix-bottom {
	position: absolute;
	top: auto;
	bottom: 270px;
}
/* Tableaux
-------------------------------------------------- */

.table {
	position: relative;
	z-index: 10;
	margin-top: 15px;
	border-collapse: separate;
	border-spacing: 0px 10px;
	margin-bottom: 0px;
	padding-bottom: 0px;
}
td {
	background-color: #dddddd;
}
.table th, .table td {
	border-top: 0px;
	vertical-align: middle;
}
.tablehead th, .tablehead td {
	color: #375c08;
	font-size: 20px;
	background-color: transparent;
}

tfoot td {
	background-color: transparent;
	color: #375c08;
	font-stretch: narrower;
	font-size: 12px;
	padding: 0px;
	margin: 0px;
	line-height: 12px;
}
table .btn {
	font-size: 13px;
}
/* Carrousel
-------------------------------------------------- */

.titre-caroussel {
	position: relative;
	top: 60px;
	margin-top: -50px;
	z-index: 20;
}
.carousel {
	margin: 0px;
	padding: 0Px;
	width: 100%;
}
.carousel-inner {
	width: 100%;
	text-align: center;
	overflow: hidden;
	position:relative;
	
}
.carousel .item {
    background-position: center center;
    background-repeat: no-repeat;
    background-size: cover;
	height: 460px;
}
.carousel-control {
	position: absolute;
	top: 150px;
}
.carousel-caption {
	background-color: transparent;
	background-image: none;
	position: absolute;
	top: 40px;
	text-align: right;
}
.carousel-caption h4, .carousel-caption p {
	font-size: 20px;
}
.modal-body .carousel-control {
	position: absolute;
	top: 50%;
}
.modal-body .carousel-caption {
	text-align: center;
	position: absolute;
	top: auto;
}
/* Icones Ressources
------------------------- */
.Icone-thumb {
	list-style: none;
}
.Icone-thumb li {
	float: left;
	margin-bottom: 5px;
	margin-left: 20px;
	height: 105px;
}
.Icone-thumb img {
	width: 80px;
}

/* Icones Categorie
------------------------- */
.icone-categorie {
	padding-right : 6px;
}
.icone-categorie img {
	margin-top: 5px;
	padding: 3px;
	border-radius: 3px;
	max-width: 40px;
	width: 40px;
}

.icone-categorie div {
	display:inline;
}

.icone-large img {
	max-width: 100px;
	width: 100px;
	margin-top: 0px;
	margin-bottom: 3px;
}

.liste-transparente .icone-categorie img {
	max-width: 53px;
	width:53px;
}

.info-listes .icone-categorie {
	margin-top: 10px;
	margin-bottom: 15px;
	}


/* Ajuste dropdown pour colorpicker
------------------------- */
.dropdown-menu {
	z-index: 2000 !important;
}
.modal .popover {
	z-index: 12000 !important;
}
/* filtre sepia pour pays archives
------------------------- */
.sepia img {
	filter: sepia(1);
	-webkit-filter: sepia(1);
	-moz-filter: sepia(1);
	-o-filter: sepia(1);
	-ms-filter: sepia(1);
}
.sepia img:hover {
	filter: sepia(0);
	-webkit-filter: sepia(0);
	-moz-filter: sepia(0);
	-o-filter: sepia(0);
	-ms-filter: sepia(0);
}
.sepia .titre-vert img {
	filter: sepia(0);
	-webkit-filter: sepia(0);
	-moz-filter: sepia(0);
	-o-filter: sepia(0);
	-ms-filter: sepia(0);
}
.sepia #map img:hover, .sepia #mapPosition img:hover {
	filter: sepia(1);
	-webkit-filter: sepia(1);
	-moz-filter: sepia(1);
	-o-filter: sepia(1);
	-ms-filter: sepia(1);
}
/* Modules ressources mondiales institut economie
------------------------- */

.ressources {
	background: #1d459e;
	color: #FFFFFF;
}
.ressources h3 {
	color: #FFFFFF;
	font-weight: bolder;
	line-height:normal;
}
.ressources p {
	text-align:left;
}
.ressources img {
	max-width: 100px;
	float: left;
}
.liste-ressources img {
	max-width: 50px;
	position: relative;
	z-index: 1024;
}

.liste-ressources h3 {
	text-align:right;
	margin-top: 5px;
	
}

.liste-ressources h4 {
	text-align:right;
	margin-top: 0px;
}

.icone-ressources img{
	max-width: 30px;
	width: 30px;
	margin-top: -5px;
	margin-right: 5px;
	float:left;
}

/* Images modal ressources
------------------------- */
.img-modal-ressource{
	height: 300px;
	margin-bottom: 10px;
}

.list-thumb-ressource{
	overflow:hidden;
}

.img-thumb-ressource{
	height: 50px;
	width: 89px;
	margin-bottom: 10px;
	cursor:pointer;
}

/* Listes temperance
------------------------- */

.liste-statut-temp{
	color:rgba(255,255,255,1);
	font-size:16px;
	padding: 0px;
	margin:0px;
	text-align:center;
}

.liste-statut-temp p{
	text-align:center;
	margin-top:12px;
}

.liste-temp div em{
	position:relative;
	top: 2px;
}

/* Ajuste images token
------------------------- */
.token-list-eco img{
	position:relative;
	top: -10px;
	margin-bottom: -15px;
}

/* Divers
------------------------- */

.pagination {
	position: relative;
	z-index: 1024;
}
.img-jeu {
	margin-bottom: 10px;
}
.img-drapeau-hist {
	width:40px;
	position:relative;
	z-index: 2;
	bottom: 24px;
}

.img-inf-off {
	width:40px;
	position:relative;
	z-index: 2;
	bottom: 40px;
}

.img-mp {
	width:40px;
}

/* FORUM GC
------------------------- */

.cache-forum {
	height: 200px;
	margin-right: -25px;
	overflow:hidden;
	}

.forum {
	position:relative;
	bottom: 16px;
	right: 25px;
}



/* ---------------------------------------------------------------------------------------------------- 
Responsive
---------------------------------------------------------------------------------------------------- */


/* ---------------------------------------------------------------------------------------------------- 
Desktop large
---------------------------------------------------------------------------------------------------- */
@media (min-width: 1200px) {
/* Augmente taille liens NavBar */
.navbar .nav > li > a {
	padding-right: 15px;
	padding-left: 15px;
}

.bienvenue {
	visibility: visible;
}

/* Ajustements police header */

.jumbotron h1 {
	font-size: 3em;
	line-height: 1em;
}
.jumbotron p {
	font-size: 1.2em;
	line-height: 1.2em;
}
.Master-link p {
	font-size: 1.5em;
}
.header p {
	margin-left: 200px;
	margin-right: 200px;
}
.bandeau-stat li {
	margin-left: 94px;
}
/* Adjust sidenav width */
.bs-docs-sidenav {
	margin-top: -96px;
}
.bs-docs-sidenav {
	width: 258px;
}
.bs-docs-sidenav > li:first-child > a {
	height: 80px;
}


/* Img categorie index
-------------------------------------------------- */
.liste-transparente .icone-categorie img {
	max-width: 62px;
	width:62px;
}
}



/* ---------------------------------------------------------------------------------------------------- 
IPAD / TABLET 
---------------------------------------------------------------------------------------------------- */


@media (max-width: 980px) {
/* Unfloat brand */
body > .navbar-fixed-top .brand {
	margin-left: 4px;
	margin-right: 5px;
	padding-left: 0px;
	padding-right: 0px;
}
/* When affixed, space properly */
.bs-docs-sidenav {
	top: 0;
	margin-top: -116px;
	margin-right: 0;
}

/* Liste token
-------------------------------------------------- */
.token {
	margin-left: 50px;
}
.bienvenue {
	visibility: hidden;
}

}



/* ---------------------------------------------------------------------------------------------------- 
Tablet to desktop
---------------------------------------------------------------------------------------------------- */

@media (min-width: 768px) and (max-width: 979px) {
/* remonte le logo*/
body > .navbar-fixed-top .brand {
	margin-left: 5px;
	padding-left: 0px;
	padding-right: 10px;
	margin-top: 0px;
}
/* remonte les icones du menu*/
.icon-accueil, .icon-carte, .icon-pays, .icon-institut, .icon-evenement, .icon-participer, .icon-generation_city {
	margin-top: -20px;
}
/* Ajuste le style du Bouton connexion*/
.btn-connexion {
	position: relative;
	top: -2px;
	margin-top: 0px;
	padding-top: 2px;
	padding-bottom: 2px;
	-webkit-box-shadow: 0px 0px 0px 0px rgba(0, 0, 0, 0);
	box-shadow: 0px 0px 0px 0px rgba(0, 0, 0, 0);
	border-width: 1px;
	border-color: #888888 #888888 #888888 #888888;
	border-color: rgba(136, 136, 136, 1) rgba(136, 136, 136, 1) rgba(136, 136, 136, 1) rgba(136, 136, 136, 1);
}
/* Augmente taille liens NavBar */
.navbar .nav > li > a {
	padding-right: 10px;
	padding-left: 10px;
}
.navbar form {
	display: inline-block;
	margin-bottom: 0px;
	margin-left: 0px;
}
.navbar-form {
	float: right;
}
.menu-gestion {
	position: relative;
	z-index: 3;
	margin-bottom: -33px;
}
.menu-gestion-front {
	left: 305px;
}
/* Ajustements police header */

.jumbotron .container-carousel {
	position: relative;
	height: 380px;
}
.jumbotron h1 {
	font-size: 2em;
	line-height: 1em;
}
.jumbotron p {
	font-size: 1.1em;
	line-height: 1.1em;
}
.Master-link p {
	font-size: 1.5em;
}
.header p {
	margin-left: 50px;
	margin-right: 50px;
}
/* Ajustements marges bandeau stat */

.bandeau-stat .container {
	padding-bottom: 0px;
}
.bandeau-stat ul {
	padding-left: 0px;
}
.bandeau-stat li {
	margin-top: 30px;
	margin-left: 10px;
	margin-bottom: 10px;
}
.bandeau-stat .label {
	font-size: 2.5em;
	padding-top: 15px;
}
.live-stat {
	float: right;
	font-size: 0.8em;
	margin-top: 0px;
	margin-left: 0px;
	padding: 0px 3px 3px 3px;
	background: #2c66ea; /* Old browsers */
	background: -moz-linear-gradient(top, #2c66ea 0%, #275dd4 100%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #2c66ea), color-stop(100%, #275dd4)); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(top, #2c66ea 0%, #275dd4 100%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(top, #2c66ea 0%, #275dd4 100%); /* Opera 11.10+ */
	background: -ms-linear-gradient(top, #2c66ea 0%, #275dd4 100%); /* IE10+ */
	background: linear-gradient(to bottom, #2c66ea 0%, #275dd4 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#2c66ea', endColorstr='#275dd4', GradientType=0 ); /* IE6-9 */
	width: 120px;
	border-radius: 2px;
}
/* listes
-------------------------------------------------- */

.info-listes {
	display: block;
}
.img-listes {
	margin: 0px;
	padding: 0px 5px;
}
/* footer
-------------------------------------------------- */
footer ul {
	display: block;
	text-align: left;
}
footer li {
	display: block;
	text-align: left;
	line-height: 1em;
	font-size: 0.8em;
	margin-bottom: 3px;
}
/* Adjust sidenav width */
.bs-docs-sidenav {
	width: 166px;
	margin-top: -145px;
}
.bs-docs-sidenav.affix {
	top: 270px;
}
.bs-docs-sidenav > li > a > img {
	width: 40px;
	max-height: 40px;
	margin-right: 5px;
}
.bs-docs-sidenav > li:first-child > a {
	height: 130px;
}
/* Liste token
-------------------------------------------------- */
.token {
	font-size: 14px;
	margin-left: 40px;
}
/* Icones Categorie
------------------------- */
.icone-categorie {
	padding: 7px;
}
.icone-categorie img {
	padding: 2px;
	border-radius: 2px;
	max-width: 30px;
	width: 30px;
}

.icone-categorie div {
	display:inline;
}

.icone-large img {
	max-width: none;
	width: 80px;
	margin-top: 0px;
	margin-bottom: 3px;
}

.liste-transparente .icone-categorie img {
	max-width: 39px;
	width:39px;
	position: relative;
	left: -10px;
}

.img-drapeau-hist {
	width:20px;
	height:15px;
	position:relative;
	z-index: 2;
	bottom: 18px;
}

.img-inf-off {
	width:40px;
	height:40px;
	position:relative;
	z-index: 2;
	bottom: 40px;
}

.ressources h3, .liste-ressources h3 {
	font-size: 16px;
}

.liste-ressources img {
	max-width: 30px;
	width: 30px;
}

.icone-ressources img{
	max-width: 20px;
	width: 20px;
}

.icone-ressources p{
	font-size: 11px;
}
}
/* ---------------------------------------------------------------------------------------------------- 
Tablet COLLAPSE
---------------------------------------------------------------------------------------------------- */
@media (max-width: 767px) {
/* btn
-------------------------------------------------- */
body {
	padding-top: 0px;
	position: relative;
}
.anchor {
	position: relative;
	margin-top: 0px;
	padding-top: 0px;
}
.btn-large {
	font-size: 1.1em;
	padding-left: 10px;
	padding-right: 10px;
}
p {
	text-align: left;
}
/* remonte le logo*/
body > .navbar .brand {
	margin-left: 5px;
	padding-left: 0px;
	padding-right: 10px;
	margin-top: 0px;
}
/* change couleur barre menu*/
.navbar-inner {
	background-image: none;
	background-repeat: no-repeat;
	background: #417109; /* Old browsers */
	background: linear-gradient(to bottom, #67b80e 0%, #3c6809 100%); /* W3C */
	border-bottom-width: 1px;
	border-top-width: 1px;
	border-color: #ffffff;
}
/* remonte les icones du menu*/
.icon-accueil, .icon-carte, .icon-pays, .icon-institut, .icon-evenement, .icon-participer, .icon-generation_city {
	margin-top: -30px;
	bottom: -30px;
	padding: 0px;
}
/* Collapse style
-------------------------------------------------- */
button.btn.btn-navbar, button.btn.btn-navbar.collapsed {
	border-radius: 0px;
	background-color: #1c4297;
 *background-color: #2c66ea;
	background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#1c4297), to(#2c66ea));
	background-image: -webkit-linear-gradient(top, #1c4297, #2c66ea);
	background-image: -o-linear-gradient(top, #1c4297, #2c66ea);
	background-image: linear-gradient(to bottom, #1c4297, #2c66ea);
	background-image: -moz-linear-gradient(top, #1c4297, #2c66ea);
	background-repeat: repeat-x;
	border-color: #ffffff #ffffff #ffffff #ffffff;
	border-color: rgba(250, 250, 250, 1) rgba(250, 250, 250, 1) rgba(250, 250, 0, 1) rgba(250, 250, 0, 1);
 filter: progid:dximagetransform.microsoft.gradient(startColorstr='#2c66ea', endColorstr='#1c4297', GradientType=0);
 filter: progid:dximagetransform.microsoft.gradient(enabled=false);
}
button.btn.btn-navbar:focus, button.btn.btn-navbar:hover, button.btn.btn-navbar:active, button.btn.btn-navbar.active, button.btn.btn-navbar.disabled, button.btn.btn-navbar[disabled], button.btn.btn-navbar.collapsed:focus, button.btn.btn-navbar.collapsed:hover, button.btn.btn-navbar.collapsed:active, button.btn.btn-navbar.collapsed.active, button.btn.btn-navbar.collapsed.disabled, button.btn.btn-navbar.collapsed[disabled] {
	background-color: #2c66ea;
 *background-color: #1c4297;
}
/* Enleve l'arrondi des boutons dans le menu collapse */
.nav-collapse .btn {
	border-radius: 0px;
}
/* Formulaires de connexion et de gestion */

.navbar-form {
	width: 150px;
}
.btn-nav-gestion {
	float: none;
}
.btn-nav-pays {
	position: relative;
	top: 0px;
}

.offset-barre{
	position: relative;
	top: 20px;
	margin: 20px;
	}

.Nav-pseudo {
	float: right;
	color: #ffffff;
	font-size: 0.8em;
	position: relative;
	top: 6px;
	text-shadow: 1px 0px 1px rgba(0,0,0,.3);
}
.nav-collapse input {
	margin-left: -12px;
}
.btn-connexion {
	margin-left: -11px;
	width: 149px;
}
.navbar form {;
	margin-left: 0px;
}
.navbar-form {
	text-align: left;
}
.nav-collapse .dropdown-menu a {
	padding-top: 9px;
	padding-right: 15px;
	padding-bottom: 9px;
	padding-left: 15px;
	font-weight: bold;
	color: #ffffff;
	border-radius: 0px;
}
.nav-collapse .nav > li > a:hover, .nav-collapse .dropdown-menu a:hover {
	background-color: #1c4297;
}
.nav-collapse .nav .nav-header {
	color: #e3e3e3;
	font-size: 0.9em;
	text-shadow: none;
}
.dropdown-menu li > a:hover, .dropdown-menu li > a:focus, .dropdown-submenu:hover > a {
	color: #ffffff;
	background-color: #1c4297;
	background-image: none;
	background-repeat: repeat-x;
}
/* Dropdown double
-------------------------------------------------- */
.dropdown-double {
	width: auto;
}
.drop-colonne-gauche {
	float: none;
	width: auto;
}
.drop-colonne-droite {
	float: none;
	width: auto;
	left: auto;
}
ul.dropdown-menu.dropdown-double > div.drop-colonne-gauche > li.nav-header {
	width: auto;
}
ul.dropdown-menu.dropdown-double > div.drop-colonne-gauche > li.divider {
	width: auto;
}
ul.dropdown-menu.dropdown-double > div.drop-colonne-gauche > li, ul.dropdown-menu.dropdown-double > div.drop-colonne-gauche > li > a, ul.dropdown-menu.dropdown-double > div.drop-colonne-gauche > li > a:hover, ul.dropdown-menu.dropdown-double > div.drop-colonne-gauche > li > a:active, ul.dropdown-menu.dropdown-double > div.drop-colonne-gauche > li > a:link, ul.dropdown-menu.dropdown-double > div.drop-colonne-gauche > li > a:visited {
	width: auto;
}
ul.dropdown-menu.dropdown-double > div.drop-colonne-droite > li.nav-header {
	width: auto;
}
ul.dropdown-menu.dropdown-double > div.drop-colonne-droite > li.divider {
	width: auto;
}
ul.dropdown-menu.dropdown-double > div.drop-colonne-droite > li, ul.dropdown-menu.dropdown-double > div.drop-colonne-droitee > li > a, ul.dropdown-menu.dropdown-double > div.drop-colonne-droite > li > a:hover, ul.dropdown-menu.dropdown-double > div.drop-colonne-droite > li > a:active, ul.dropdown-menu.dropdown-double > div.drop-colonne-droite > li > a:link, ul.dropdown-menu.dropdown-double > div.drop-colonne-droite > li > a:visited {
	width: auto;
}
/* Adapte les marges au changement de taille */
.jumbotron {
	padding: 20px 20px;
	margin-right: -20px;
	margin-left: -20px;
	margin-top: -20px;
	background-position: bottom;
}
.jumbotron .container {
	position: relative;
	z-index: 2;
	height: 342px;
	overflow: hidden;
}
.jumbotron .container-carousel {
	position: relative;
	height: 342px;
	overflow: hidden;
	padding: 0px 0px;
	margin: -20px;
}
.jumbotron-institut {
	background-origin: border-box;
}
/* Ajustements police header */

.jumbotron h1 {
	font-size: 2em;
	line-height: 1em;
}
.jumbotron p {
	font-size: 1.1em;
	line-height: 1.1em;
}
.Master-link p {
	font-size: 1.5em;
}
.header p {
	margin-left: 20px;
	margin-right: 20px;
}
/* Ajustements marges bandeau stat */

.bandeau-stat {
	padding: 0px -20px 0px -20px;
	margin-right: -20px;
	margin-left: -20px;
}
.bandeau-stat .container {
	background-image: none;
}
.bandeau-stat ul {
	padding: 0px;
	margin: 0px;
}
.bandeau-stat li {
	margin-top: 5px;
	margin-left: 10px;
	margin-bottom: 5px;
	margin-right: 0px;
	font-size: 0.9em;
	text-shadow: none;
}
.bandeau-stat .label {
	font-size: 1.2em;
	padding: 2px 4px 2px 4px;
	background: #ffffff;
	color: #204db0;
	border-radius: 1px;
	text-shadow: none;
}
.live-stat {
	float: right;
	display: inline;
	font-size: 0.8em;
	margin: 0px 5px 0px 0px;
}
/* listes
-------------------------------------------------- */
.info-listes {
	margin-top: -30px;
}
.img-listes img {
	width: 40%;
	float: left;
	margin-right: 15px;
}

.liste-pays h3 {
	left : -18px;
}

/* Notifications 
-------------------------------------------------- */

liste-transparente {
	text-align: center;
}
.auteur img {
	display: inline;
	width: 80px;
}
/* footer
-------------------------------------------------- */

footer ul {
	display: block;
}
footer li {
	display: block;
	text-align: left;
	line-height: 1em;
	font-size: 0.8em;
	margin-bottom: 3px;
}
footer p {
	display: block;
	margin-bottom: 3px;
}
footer img {
	display: none;
	float: right;
}
.copyright {
	right: 0px;
	margin-right: 0px;
	float: right;
	display: block;
}
.copyright p {
	float: none;
	display: block;
	margin-bottom: 5px;
}
/* Input Nav bar in correct dimension*/
/* Sidenav */
.bs-docs-sidenav {
	width: auto;
	margin-top: 20px;
	margin-bottom: 0px;
}
.bs-docs-sidenav > li:first-child > a {
	height: auto;
	padding-bottom: 25px;
}
.bs-docs-sidenav.affix {
	position: static;
	width: auto;
	height: auto;
	top: 0;
}
/* Carrousel
------------------------- */

.carousel {
	overflow: hidden;
}
.carousel-inner {
	overflow: hidden;
	height: 100%;
}
.carousel-inner img {
	width: auto;
	overflow: hidden;
	height: 100%;
}

.carousel .item {
	height: 355px;
}



/* Liste token
-------------------------------------------------- */
.token {
	margin-left:15px;
}
.token li.span1 {
	float: left;
	width: 100px;
	display: inline;
}
.token img {
	width: 40px;
}
/* Icones Categorie
------------------------- */
.icone-categorie img {
	max-width: 40px;
	width: 40px;
	margin-top: 3px;
}

.icone-large img {
	max-width: none;
	width: 80px;
	margin-top: 0px;
	margin-bottom: 3px;
}

.liste-pays img {
	max-width: 110px;
}

.liste-transparente .icone-categorie img {
	max-width: 59px;
	width:59px;
}

.liste-ressources h3 {
	text-align:left;
}

/* Drapeau sur fait historique
------------------------- */
.img-drapeau-hist {
	max-width: 40px;
	width:40px;
	position:relative;
	z-index: 2;
	bottom: 0px;
	margin-right: 5px;
}

.img-inf-off {
	max-width: 40px;
	width:40px;
	position:relative;
	z-index: 2;
	bottom: 0px;
	margin-right: 5px;
}
/* Reduction avatar commentaires
------------------------- */
.img-avatar {
	max-width: 150px;
	width: 150px;
	display: block;
}

.container-fluid {
	margin: -20px;
}
#info {
	height:auto;
}
#info li img{
	width: 20px;
	display:inline;
	float:left;
}
}

/* Landscape phones
------------------------- */
@media (max-width: 480px) {
/* header */
.jumbotron {
	height: auto;
}
.jumbotron .container {
	overflow: visible;
	height: auto;
}
.jumbotron .carousel-inner {
	overflow: hidden;
	height: 200px;
	margin: 0px;
	padding: 0px;
}
.jumbotron h1 {
	font-size: 2em;
	line-height: 1em;
	margin-top: 3px;
}
.jumbotron p {
	font-size: 1.2em;
	line-height: 0.9em;
}
.header {
	padding-top: 0px;
	margin-bottom: 5px;
}
.header p {
	margin-left: 5px;
	margin-right: 5px;
	text-align: left;
}
.Master-link a {
	width: 100px;
}
/* Ajustements bandeau stat */
.bandeau-stat .container {
	background-image: none;
}
.bandeau-stat li {
	font-size: 0.8em;
}
.bandeau-stat .label {
	font-size: 1.2em;
	text-shadow: none;
	background: none;
	background-color: transparent;
	color: #ffffff;
	padding: 0px;
}
/* Ajustements Acc�s rapides */
.liens-rapides {
	text-align: left;
	margin-left: 5px;
}
/* listes
-------------------------------------------------- */
.info-listes {
	display: block;
	float: none;
	margin-top: 20px;
	margin-left: 0px;
	width: 100%;
}
.img-listes img {
	display: block;
	width: 100%;
	margin: 0px;
	padding: 0px;
	padding-bottom: 10px;
}


.img-avatar img{
	max-width: 100px;
	width: 100px;
	display: block;
	margin-right: 5px;
}

.listes-visiteurs h4{
	clear:both;
}

/* footer
-------------------------------------------------- */

footer {
}
footer p {
	text-align: right;
	float: right;
}
.copyright {
	right: 0px;
	margin-right: 0px;
	display: block;
	text-align: right;
}
footer img {
	display: none;
	float: right;
}
.copyright p {
	float: none;
	display: block;
	margin-bottom: 5px;
	text-align: right;
}
/* Carousel
-------------------------------------------------- */

.carousel {
	margin-top: -45px;
}
.titre-caroussel {
	top: 0px;
}

.carousel .item {
	height: 250px;
}


/* Drapeau sur fait historique
------------------------- */
.img-drapeau-hist {
	max-width: 40px;
	width:40px;
	position:relative;
	z-index: 2;
	bottom: 0px;
	padding-right: 10px;
	margin-right: 10px;
}

.img-inf-off{
	max-width: 40px;
	width:40px;
	position:relative;
	z-index: 2;
	bottom: 0px;
	padding-right: 10px;
	margin-right: 10px;
}

/* Boutons
-------------------------------------------------- */
.btn-ssmarge {
	margin-left: 0px;
	margin-top: 0px;
}

}


</style>
<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
<!--[if gte IE 9]>
  <style type="text/css">
    .gradient {
       filter: none;
    }
  </style>
<![endif]-->
<!-- Le fav and touch icons -->
<link rel="shortcut icon" href="../assets/ico/favicon.ico">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
<style>

.jumbotron {
	background-image: url('<?php echo $row_ch_pat_confimation_suppression['ch_pat_lien_img1']; ?>');
	background-position: center;
}

</style>
</head>

<body data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140" onLoad="init()">
<!-- Navbar
    ================================================== -->
<?php include('../php/navbarback.php'); ?>
<!-- Subhead
================================================== -->
<header class="jumbotron subhead" id="overview">
  <div class="container">
    <h1>Attention&nbsp;!</h1>
    <p>Souhaitez-vous r&eacute;ellement supprimer <?php echo $row_ch_pat_confimation_suppression['ch_pat_nom']; ?>&nbsp;?</p>
    <p>Cette action sera irr&eacute;versible</p>
    <form action="monument_supprimer.php" method="post" class="form-button-inline">
      <input name="monument_ID" type="hidden" value="<?php echo $row_ch_pat_confimation_suppression['ch_pat_id']; ?>">
      <button type="submit" class="btn btn-large btn-danger" title="supprimer le monument"><i class="icon-trash icon-white"></i> Supprimer</button>
    </form>
    <form action="ville_modifier.php#mes-monuments" method="get" class="form-button-inline">
      <input name="monument_ID" type="hidden" value="<?php echo $row_ch_pat_confimation_suppression['ch_pat_villeID']; ?>">
      <button type="submit" class="btn btn-large btn-success" title="retour &agrave; la page de modification de la ville">Annuler</button>
    </form>
  </div>
</header>
<div class="container corps-page"> </div>

<!-- Footer
    ================================================== -->
<?php include('../php/footerback.php'); ?>
</body>
</html>
<!-- Le javascript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<!-- BOOTSTRAP -->
<script src="../assets/js/jquery.js"></script>
<script src="../assets/js/bootstrap.js"></script>
<script src="../assets/js/bootstrap-affix.js"></script>
<script src="../assets/js/application.js"></script>
<script src="../assets/js/bootstrap-scrollspy.js"></script>
<script src="../assets/js/bootstrapx-clickover.js"></script>
 <script type="text/javascript">
      $(function() { 
          $('[rel="clickover"]').clickover();})
    </script>
<?php
mysql_free_result($ch_pat_confimation_suppression);
?>
