// JavaScript Document
function echeck(str) {
  var at="@"
  var dot="."
  var lat=str.indexOf(at)
  var lstr=str.length
  var ldot=str.indexOf(dot)
  if (str.indexOf(at)==-1){
     alert("Invalid E-mail ID")
     return false
  }
  if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
     alert("Invalid E-mail ID")
     return false
  }
  if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
      alert("Invalid E-mail ID")
      return false
  }
   if (str.indexOf(at,(lat+1))!=-1){
      alert("Invalid E-mail ID")
      return false
   }
   if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
      alert("Invalid E-mail ID")
      return false
   }
   if (str.indexOf(dot,(lat+2))==-1){
      alert("Invalid E-mail ID")
      return false
   }
   if (str.indexOf(" ")!=-1){
      alert("Invalid E-mail ID")
      return false
   }
   return true          
}

function ValidateForm(){
  var emailID=document.BContact.EmailFrom
  
  if ((emailID.value==null)||(emailID.value=="")){
    alert("Please Enter your Email")
    emailID.focus()
    return false
  }
  if (echeck(emailID.value)==false){
    emailID.value=""
    emailID.focus()
    return false
  }
  return true
 }
