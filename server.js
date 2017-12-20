var express = require('express');
var bodyParser = require('body-parser')
var app = express();

var request = require('request');
var parseString = require('xml2js').parseString;
//var fs = require('fs'),
//	xml2js = require('xml2js');
//var parseString = require('xml2js').parseString;
function tofloat2(x){
          var f = parseFloat(x);
          f = Math.round(f*100)/100;
          return f;
}

app.get('/', function (req, res) {
   res.header('Access-Control-Allow-Origin','*');
   //console.log(req.query.symbol);
   if (req.query.symbol!='') {
  if(req.query.indic==''){
  var url = 'https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=' + req.query.symbol + '&outputsize=full&apikey=KQS20D4CNURTYXU7';
    request(url, function(err,response,body){
      if (!err) {
        jsonobj = JSON.parse(body);
        Rowdate = Object.keys(jsonobj["Time Series (Daily)"]);
        var Plast = tofloat2(jsonobj['Time Series (Daily)'][Rowdate[0]]['4. close']);
              var Ppreclose = tofloat2(jsonobj['Time Series (Daily)'][Rowdate[1]]['4. close']);
              var Popen = tofloat2(jsonobj['Time Series (Daily)'][Rowdate[0]]['1. open']);
              var Pchange = Plast - Ppreclose;
              Pchange = Math.round(Pchange*100)/100;
              var Pcent = Math.round(Pchange/Ppreclose*100)/100 + "%";
              Pchange = Pchange + "(" + Pcent + ")";
            
            var str = [];
            str.push(jsonobj['Meta Data']['2. Symbol'])
            str.push(Plast);
            str.push(Pchange);
            str.push(Rowdate[0]);
            str.push(Popen);
            str.push(Ppreclose);
            str.push(jsonobj['Time Series (Daily)'][Rowdate[0]]['3. low'] + "-" + jsonobj['Time Series (Daily)'][Rowdate[0]]['2. high']);
            str.push(jsonobj['Time Series (Daily)'][Rowdate[0]]['5. volume']);
        //console.log('Done');
        res.send(str);
      }
   });
   }
   else if(req.query.indic=='news1'){
      var url = "https://seekingalpha.com/api/sa/combined/" + req.query.symbol + ".xml";
    request(url, function(err,response,body){
      if (!err) {
        parseString(body, function(err, result){
          //console.log('Done');
          item = result.rss.channel["0"].item;
          var count = 0;
          var str2 = [];
          for(var i=0; i < item.length; i++){
            NodeL = item[i];
            if(NodeL["link"]["0"].split("/")[3]=="article"){
            strt = NodeL["pubDate"]["0"].substr(0,NodeL["pubDate"]["0"].length-6);
            Author = "Author:" + NodeL["sa:author_name"]["0"];
            htmnews = "<a href=" + NodeL["link"]["0"] + ">" + NodeL["title"]["0"] + "</a><br/>" + Author + "<br/>Date:" + strt ;
            count++;
            //console.log(NodeL["link"]);
            str2.push(htmnews);
          }
          if(count==5){
            break;
          }
        }   
          res.send(str2);
        });
      }
   });
   } 

   }
   

   //res.send('Hello World' + req.query.symbol);
   
})

var server = app.listen(8081, function () {
 
  var host = server.address().address
  var port = server.address().port
 
  //console.log("应用实例，访问地址为 http://%s:%s", host, port)
 
})