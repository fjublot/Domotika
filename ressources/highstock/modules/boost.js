/*
 Highcharts JS v6.0.4 (2017-12-15)
 Boost module

 (c) 2010-2017 Highsoft AS
 Author: Torstein Honsi

 License: www.highcharts.com/license
*/
(function(v){"object"===typeof module&&module.exports?module.exports=v:v(Highcharts)})(function(v){(function(l){function v(){var a=Array.prototype.slice.call(arguments),c=-Number.MAX_VALUE;y(a,function(a){if("undefined"!==typeof a&&null!==a&&"undefined"!==typeof a.length&&0<a.length)return c=a.length,!0});return c}function K(a){var c=0,d=0,f=C(a.options.boost&&a.options.boost.allowForce,!0),b;if("undefined"!==typeof a.boostForceChartBoost)return a.boostForceChartBoost;if(1<a.series.length)for(var m=
0;m<a.series.length;m++)b=a.series[m],L[b.type]&&++d,v(b.processedXData,b.options.data,b.points)>=(b.options.boostThreshold||Number.MAX_VALUE)&&++c;a.boostForceChartBoost=f&&d===a.series.length&&0<c||5<c;return a.boostForceChartBoost}function ka(a){function c(b,c){c=a.createShader("vertex"===c?a.VERTEX_SHADER:a.FRAGMENT_SHADER);a.shaderSource(c,b);a.compileShader(c);return a.getShaderParameter(c,a.COMPILE_STATUS)?c:!1}function d(){function b(b){return a.getUniformLocation(m,b)}var d=c("#version 100\nprecision highp float;\nattribute vec4 aVertexPosition;\nattribute vec4 aColor;\nvarying highp vec2 position;\nvarying highp vec4 vColor;\nuniform mat4 uPMatrix;\nuniform float pSize;\nuniform float translatedThreshold;\nuniform bool hasThreshold;\nuniform bool skipTranslation;\nuniform float plotHeight;\nuniform float xAxisTrans;\nuniform float xAxisMin;\nuniform float xAxisMinPad;\nuniform float xAxisPointRange;\nuniform float xAxisLen;\nuniform bool  xAxisPostTranslate;\nuniform float xAxisOrdinalSlope;\nuniform float xAxisOrdinalOffset;\nuniform float xAxisPos;\nuniform bool  xAxisCVSCoord;\nuniform float yAxisTrans;\nuniform float yAxisMin;\nuniform float yAxisMinPad;\nuniform float yAxisPointRange;\nuniform float yAxisLen;\nuniform bool  yAxisPostTranslate;\nuniform float yAxisOrdinalSlope;\nuniform float yAxisOrdinalOffset;\nuniform float yAxisPos;\nuniform bool  yAxisCVSCoord;\nuniform bool  isBubble;\nuniform bool  bubbleSizeByArea;\nuniform float bubbleZMin;\nuniform float bubbleZMax;\nuniform float bubbleZThreshold;\nuniform float bubbleMinSize;\nuniform float bubbleMaxSize;\nuniform bool  bubbleSizeAbs;\nuniform bool  isInverted;\nfloat bubbleRadius(){\nfloat value \x3d aVertexPosition.w;\nfloat zMax \x3d bubbleZMax;\nfloat zMin \x3d bubbleZMin;\nfloat radius \x3d 0.0;\nfloat pos \x3d 0.0;\nfloat zRange \x3d zMax - zMin;\nif (bubbleSizeAbs){\nvalue \x3d value - bubbleZThreshold;\nzMax \x3d max(zMax - bubbleZThreshold, zMin - bubbleZThreshold);\nzMin \x3d 0.0;\n}\nif (value \x3c zMin){\nradius \x3d bubbleZMin / 2.0 - 1.0;\n} else {\npos \x3d zRange \x3e 0.0 ? (value - zMin) / zRange : 0.5;\nif (bubbleSizeByArea \x26\x26 pos \x3e 0.0){\npos \x3d sqrt(pos);\n}\nradius \x3d ceil(bubbleMinSize + pos * (bubbleMaxSize - bubbleMinSize)) / 2.0;\n}\nreturn radius * 2.0;\n}\nfloat translate(float val,\nfloat pointPlacement,\nfloat localA,\nfloat localMin,\nfloat minPixelPadding,\nfloat pointRange,\nfloat len,\nbool  cvsCoord\n){\nfloat sign \x3d 1.0;\nfloat cvsOffset \x3d 0.0;\nif (cvsCoord) {\nsign *\x3d -1.0;\ncvsOffset \x3d len;\n}\nreturn sign * (val - localMin) * localA + cvsOffset + \n(sign * minPixelPadding);\n}\nfloat xToPixels(float value){\nif (skipTranslation){\nreturn value;// + xAxisPos;\n}\nreturn translate(value, 0.0, xAxisTrans, xAxisMin, xAxisMinPad, xAxisPointRange, xAxisLen, xAxisCVSCoord);// + xAxisPos;\n}\nfloat yToPixels(float value, float checkTreshold){\nfloat v;\nif (skipTranslation){\nv \x3d value;// + yAxisPos;\n} else {\nv \x3d translate(value, 0.0, yAxisTrans, yAxisMin, yAxisMinPad, yAxisPointRange, yAxisLen, yAxisCVSCoord);// + yAxisPos;\nif (v \x3e plotHeight) {\nv \x3d plotHeight;\n}\n}\nif (checkTreshold \x3e 0.0 \x26\x26 hasThreshold) {\nv \x3d min(v, translatedThreshold);\n}\nreturn v;\n}\nvoid main(void) {\nif (isBubble){\ngl_PointSize \x3d bubbleRadius();\n} else {\ngl_PointSize \x3d pSize;\n}\nvColor \x3d aColor;\nif (isInverted) {\ngl_Position \x3d uPMatrix * vec4(xToPixels(aVertexPosition.y) + yAxisPos, yToPixels(aVertexPosition.x, aVertexPosition.z) + xAxisPos, 0.0, 1.0);\n} else {\ngl_Position \x3d uPMatrix * vec4(xToPixels(aVertexPosition.x) + xAxisPos, yToPixels(aVertexPosition.y, aVertexPosition.z) + yAxisPos, 0.0, 1.0);\n}\n}",
"vertex"),f=c("precision highp float;\nuniform vec4 fillColor;\nvarying highp vec2 position;\nvarying highp vec4 vColor;\nuniform sampler2D uSampler;\nuniform bool isCircle;\nuniform bool hasColor;\nvoid main(void) {\nvec4 col \x3d fillColor;\nvec4 tcol;\nif (hasColor) {\ncol \x3d vColor;\n}\nif (isCircle) {\ntcol \x3d texture2D(uSampler, gl_PointCoord.st);\ncol *\x3d tcol;\nif (tcol.r \x3c 0.0) {\ndiscard;\n} else {\ngl_FragColor \x3d col;\n}\n} else {\ngl_FragColor \x3d col;\n}\n}","fragment");
if(!d||!f)return m=!1;m=a.createProgram();a.attachShader(m,d);a.attachShader(m,f);a.linkProgram(m);a.useProgram(m);a.bindAttribLocation(m,0,"aVertexPosition");l=b("uPMatrix");n=b("pSize");g=b("fillColor");S=b("isBubble");h=b("bubbleSizeAbs");A=b("bubbleSizeByArea");y=b("uSampler");e=b("skipTranslation");z=b("isCircle");k=b("isInverted");E=b("plotHeight");return!0}function f(c,e){c=b[c]=b[c]||a.getUniformLocation(m,c);a.uniform1f(c,e)}var b={},m,l,n,g,S,h,A,e,z,k,E,y;a&&d();return{psUniform:function(){return n},
pUniform:function(){return l},fillColorUniform:function(){return g},setPlotHeight:function(b){a.uniform1f(E,b)},setBubbleUniforms:function(b,c,e){var d=b.options,m=Number.MAX_VALUE,l=-Number.MAX_VALUE;"bubble"===b.type&&(m=C(d.zMin,Math.min(m,Math.max(c,!1===d.displayNegative?d.zThreshold:-Number.MAX_VALUE))),l=C(d.zMax,Math.max(l,e)),a.uniform1i(S,1),a.uniform1i(z,1),a.uniform1i(A,"width"!==b.options.sizeBy),a.uniform1i(h,b.options.sizeByAbsoluteValue),f("bubbleZMin",m),f("bubbleZMax",l),f("bubbleZThreshold",
b.options.zThreshold),f("bubbleMinSize",b.minPxSize),f("bubbleMaxSize",b.maxPxSize))},bind:function(){a.useProgram(m)},program:function(){return m},create:d,setUniform:f,setPMatrix:function(b){a.uniformMatrix4fv(l,!1,b)},setColor:function(b){a.uniform4f(g,b[0]/255,b[1]/255,b[2]/255,b[3])},setPointSize:function(b){a.uniform1f(n,b)},setSkipTranslation:function(b){a.uniform1i(e,!0===b?1:0)},setTexture:function(){a.uniform1i(y,0)},setDrawAsCircle:function(b){a.uniform1i(z,b?1:0)},reset:function(){a.uniform1i(S,
0);a.uniform1i(z,0)},setInverted:function(b){a.uniform1i(k,b)},destroy:function(){a&&m&&(a.deleteProgram(m),m=!1)}}}function ba(a,c,d){function f(){b&&(a.deleteBuffer(b),m=b=!1);g=0;l=d||2;k=[]}var b=!1,m=!1,l=d||2,n=!1,g=0,k;return{destroy:f,bind:function(){if(!b)return!1;a.vertexAttribPointer(m,l,a.FLOAT,!1,0,0)},data:k,build:function(d,g,e){var h;k=d||[];if(!(k&&0!==k.length||n))return f(),!1;l=e||l;b&&a.deleteBuffer(b);n||(h=new Float32Array(k));b=a.createBuffer();a.bindBuffer(a.ARRAY_BUFFER,
b);a.bufferData(a.ARRAY_BUFFER,n||h,a.STATIC_DRAW);m=a.getAttribLocation(c.program(),g);a.enableVertexAttribArray(m);return!0},render:function(c,d,e){var f=n?n.length:k.length;if(!b||!f)return!1;if(!c||c>f||0>c)c=0;if(!d||d>f)d=f;a.drawArrays(a[(e||"points").toUpperCase()],c/l,(d-c)/l);return!0},allocate:function(a){g=-1;n=new Float32Array(4*a)},push:function(a,b,c,d){n&&(n[++g]=a,n[++g]=b,n[++g]=c,n[++g]=d)}}}function la(a){function c(a){var b,c;return a.isSeriesBoosting?(b=!!a.options.stacking,
c=a.xData||a.options.xData||a.processedXData,b=(b?a.data:c||a.options.data).length,"treemap"===a.type?b*=12:"heatmap"===a.type?b*=6:N[a.type]&&(b*=2),b):0}function d(){e.clear(e.COLOR_BUFFER_BIT|e.DEPTH_BUFFER_BIT)}function f(a,b){function c(a){a&&(b.colorData.push(a[0]),b.colorData.push(a[1]),b.colorData.push(a[2]),b.colorData.push(a[3]))}function d(a,b,d,e,f){c(f);q.usePreallocated?A.push(a,b,d?1:0,e||1):(E.push(a),E.push(b),E.push(d?1:0),E.push(e||1))}function e(a,b,e,f,r){c(r);d(a+e,b);c(r);d(a,
b);c(r);d(a,b+f);c(r);d(a,b+f);c(r);d(a+e,b+f);c(r);d(a+e,b)}function f(a){q.useGPUTranslations||(b.skipTranslation=!0,a.x=H.toPixels(a.x,!0),a.y=J.toPixels(a.y,!0));d(a.x,a.y,0,2)}var ma=a.pointArrayMap&&"low,high"===a.pointArrayMap.join(","),h=a.chart,r=a.options,m=!!r.stacking,g=r.data,n=a.xAxis.getExtremes(),k=n.min,n=n.max,w=a.yAxis.getExtremes(),z=w.min,w=w.max,p=a.xData||r.xData||a.processedXData,D=a.yData||r.yData||a.processedYData,v=a.zData||r.zData||a.processedZData,J=a.yAxis,H=a.xAxis,
Z=a.chart.plotHeight,T=!p||0===p.length,t=a.points||!1,F=!1,C=!1,x,Q,R,g=m?a.data:p||g,p={x:Number.MIN_VALUE,y:0},P={x:Number.MIN_VALUE,y:0},M=0,u,S,B=-1,I=!1,G=!1,Y="undefined"===typeof h.index,O=!1,U=!1,K=N[a.type],L=!1,aa=!0;if(!(r.boostData&&0<r.boostData.length))if(a.closestPointRangePx=Number.MAX_VALUE,t&&0<t.length)b.skipTranslation=!0,b.drawMode="triangles",t[0].node&&t[0].node.levelDynamic&&t.sort(function(a,b){if(a.node){if(a.node.levelDynamic>b.node.levelDynamic)return 1;if(a.node.levelDynamic<
b.node.levelDynamic)return-1}return 0}),y(t,function(b){var c=b.plotY,d;"undefined"===typeof c||isNaN(c)||null===b.y||(c=b.shapeArgs,d=b.series.pointAttribs(b),b=d["stroke-width"]||0,Q=l.color(d.fill).rgba,Q[0]/=255,Q[1]/=255,Q[2]/=255,"treemap"===a.type&&(b=b||1,R=l.color(d.stroke).rgba,R[0]/=255,R[1]/=255,R[2]/=255,e(c.x,c.y,c.width,c.height,R),b/=2),"heatmap"===a.type&&h.inverted&&(c.x=H.len-c.x,c.y=J.len-c.y,c.width=-c.width,c.height=-c.height),e(c.x+b,c.y+b,c.width-2*b,c.height-2*b,Q))});else{for(;B<
g.length-1;){x=g[++B];if(Y)break;T?(t=x[0],u=x[1],g[B+1]&&(G=g[B+1][0]),g[B-1]&&(I=g[B-1][0]),3<=x.length&&(S=x[2],x[2]>b.zMax&&(b.zMax=x[2]),x[2]<b.zMin&&(b.zMin=x[2]))):(t=x,u=D[B],g[B+1]&&(G=g[B+1]),g[B-1]&&(I=g[B-1]),v&&v.length&&(S=v[B],v[B]>b.zMax&&(b.zMax=v[B]),v[B]<b.zMin&&(b.zMin=v[B])));G&&G>=k&&G<=n&&(O=!0);I&&I>=k&&I<=n&&(U=!0);ma?(T&&(u=x.slice(1,3)),u=u[1]):m&&(t=x.x,u=x.stackY);a.requireSorting||(aa=u>=z&&u<=w);t>n&&P.x<n&&(P.x=t,P.y=u);t<k&&p.x<k&&(p.x=t,p.y=u);if(0===u||u&&aa)if(t>=
k&&t<=n&&(L=!0),L||O||U)q.useGPUTranslations||(b.skipTranslation=!0,t=H.toPixels(t,!0),u=J.toPixels(u,!0),u>Z&&(u=Z)),K&&(x=0,0>u&&(x=u,u=0),q.useGPUTranslations||(x=J.toPixels(x,!0)),d(t,x,0,0,!1)),b.hasMarkers&&!1!==F&&(a.closestPointRangePx=Math.min(a.closestPointRangePx,Math.abs(t-F))),!q.useGPUTranslations&&!q.usePreallocated&&F&&1>t-F&&C&&1>Math.abs(u-C)?q.debug.showSkipSummary&&++M:(r.step&&d(t,C,0,2,!1),d(t,u,0,"bubble"===a.type?S||1:2,!1),F=t,C=u)}q.debug.showSkipSummary&&console.log("skipped points:",
M);F||(f(p),f(P))}}function b(){w=[];P.data=E=[];T=[];A&&A.destroy()}function m(a){h&&(h.setUniform("xAxisTrans",a.transA),h.setUniform("xAxisMin",a.min),h.setUniform("xAxisMinPad",a.minPixelPadding),h.setUniform("xAxisPointRange",a.pointRange),h.setUniform("xAxisLen",a.len),h.setUniform("xAxisPos",a.pos),h.setUniform("xAxisCVSCoord",!a.horiz))}function g(a){h&&(h.setUniform("yAxisTrans",a.transA),h.setUniform("yAxisMin",a.min),h.setUniform("yAxisMinPad",a.minPixelPadding),h.setUniform("yAxisPointRange",
a.pointRange),h.setUniform("yAxisLen",a.len),h.setUniform("yAxisPos",a.pos),h.setUniform("yAxisCVSCoord",!a.horiz))}function n(a,b){h.setUniform("hasThreshold",a);h.setUniform("translatedThreshold",b)}function k(c){if(c)z=c.chartWidth||800,J=c.chartHeight||400;else return!1;if(!e||!z||!J)return!1;q.debug.timeRendering&&console.time("gl rendering");e.canvas.width=z;e.canvas.height=J;h.bind();e.viewport(0,0,z,J);h.setPMatrix([2/z,0,0,0,0,-(2/J),0,0,0,0,-2,0,-1,1,-1,1]);h.setPlotHeight(c.plotHeight);
1<q.lineWidth&&!l.isMS&&e.lineWidth(q.lineWidth);A.build(P.data,"aVertexPosition",4);A.bind();v&&(e.bindTexture(e.TEXTURE_2D,H),h.setTexture(H));h.setInverted(c.inverted);y(w,function(a,b){var c=a.series.options,d=c.threshold,f=I(d),d=a.series.yAxis.getThreshold(d),r=C(c.marker?c.marker.enabled:null,a.series.xAxis.isRadial?!0:null,a.series.closestPointRangePx>2*((c.marker?c.marker.radius:10)||10)),k=a.series.pointAttribs&&a.series.pointAttribs().fill||a.series.color;a.series.fillOpacity&&c.fillOpacity&&
(k=(new ca(k)).setOpacity(C(c.fillOpacity,1)).get());c.colorByPoint&&(k=a.series.chart.options.colors[b]);k=l.color(k).rgba;q.useAlpha||(k[3]=1);"lines"===a.drawMode&&q.useAlpha&&1>k[3]&&(k[3]/=10);"add"===c.boostBlending?(e.blendFunc(e.SRC_ALPHA,e.ONE),e.blendEquation(e.FUNC_ADD)):"mult"===c.boostBlending?e.blendFunc(e.DST_COLOR,e.ZERO):"darken"===c.boostBlending?(e.blendFunc(e.ONE,e.ONE),e.blendEquation(e.FUNC_MIN)):e.blendFuncSeparate(e.SRC_ALPHA,e.ONE_MINUS_SRC_ALPHA,e.ONE,e.ONE_MINUS_SRC_ALPHA);
h.reset();0<a.colorData.length&&(h.setUniform("hasColor",1),b=ba(e,h),b.build(a.colorData,"aColor",4),b.bind());h.setColor(k);m(a.series.xAxis);g(a.series.yAxis);n(f,d);"points"===a.drawMode&&(c.marker&&c.marker.radius?h.setPointSize(2*c.marker.radius):h.setPointSize(1));h.setSkipTranslation(a.skipTranslation);"bubble"===a.series.type&&h.setBubbleUniforms(a.series,a.zMin,a.zMax);h.setDrawAsCircle(Y[a.series.type]&&v||!1);A.render(a.from,a.to,a.drawMode);a.hasMarkers&&r&&(c.marker&&c.marker.radius?
h.setPointSize(2*c.marker.radius):h.setPointSize(10),h.setDrawAsCircle(!0),A.render(a.from,a.to,"POINTS"))});q.debug.timeRendering&&console.timeEnd("gl rendering");a&&a();b()}function p(a){d();if(a.renderer.forExport)return k(a);M?k(a):setTimeout(function(){p(a)},1)}var h=!1,A=!1,e=!1,z=0,J=0,E=!1,T=!1,v=!1,P={},M=!1,w=[],F=G.createElement("canvas"),D=F.getContext("2d"),H,N={column:!0,bar:!0,area:!0},Y={scatter:!0,bubble:!0},q={pointSize:1,lineWidth:1,fillColor:"#AA00AA",useAlpha:!0,usePreallocated:!1,
useGPUTranslations:!1,debug:{timeRendering:!1,timeSeriesProcessing:!1,timeSetup:!1,timeBufferCopy:!1,timeKDTree:!1,showSkipSummary:!1}};return P={allocateBufferForSingleSeries:function(a){var b=0;q.usePreallocated&&(a.isSeriesBoosting&&(b=c(a)),A.allocate(b))},pushSeries:function(a){0<w.length&&(w[w.length-1].to=E.length,w[w.length-1].hasMarkers&&(w[w.length-1].markerTo=T.length));q.debug.timeSeriesProcessing&&console.time("building "+a.type+" series");w.push({from:E.length,markerFrom:T.length,colorData:[],
series:a,zMin:Number.MAX_VALUE,zMax:-Number.MAX_VALUE,hasMarkers:a.options.marker?!1!==a.options.marker.enabled:!1,showMarksers:!0,drawMode:{area:"lines",arearange:"lines",areaspline:"line_strip",column:"lines",bar:"lines",line:"line_strip",scatter:"points",heatmap:"triangles",treemap:"triangles",bubble:"points"}[a.type]||"line_strip"});f(a,w[w.length-1]);q.debug.timeSeriesProcessing&&console.timeEnd("building "+a.type+" series")},setSize:function(a,b){if(z!==a||b!==b)z=a,J=b,h.bind(),h.setPMatrix([2/
z,0,0,0,0,-(2/J),0,0,0,0,-2,0,-1,1,-1,1])},inited:function(){return M},setThreshold:n,init:function(a,c){var d=0,f=["webgl","experimental-webgl","moz-webgl","webkit-3d"];M=!1;if(!a)return!1;for(q.debug.timeSetup&&console.time("gl setup");d<f.length&&!(e=a.getContext(f[d],{}));d++);if(e)c||b();else return!1;e.enable(e.BLEND);e.blendFunc(e.SRC_ALPHA,e.ONE_MINUS_SRC_ALPHA);e.disable(e.DEPTH_TEST);e.depthFunc(e.LESS);h=ka(e);A=ba(e,h);v=!1;H=e.createTexture();F.width=512;F.height=512;D.mozImageSmoothingEnabled=
!1;D.webkitImageSmoothingEnabled=!1;D.msImageSmoothingEnabled=!1;D.imageSmoothingEnabled=!1;D.strokeStyle="rgba(255, 255, 255, 0)";D.fillStyle="#FFF";D.beginPath();D.arc(256,256,256,0,2*Math.PI);D.stroke();D.fill();try{e.bindTexture(e.TEXTURE_2D,H),e.texImage2D(e.TEXTURE_2D,0,e.RGBA,e.RGBA,e.UNSIGNED_BYTE,F),e.texParameteri(e.TEXTURE_2D,e.TEXTURE_WRAP_S,e.CLAMP_TO_EDGE),e.texParameteri(e.TEXTURE_2D,e.TEXTURE_WRAP_T,e.CLAMP_TO_EDGE),e.texParameteri(e.TEXTURE_2D,e.TEXTURE_MAG_FILTER,e.LINEAR),e.texParameteri(e.TEXTURE_2D,
e.TEXTURE_MIN_FILTER,e.LINEAR),e.bindTexture(e.TEXTURE_2D,null),v=!0}catch(W){}M=!0;q.debug.timeSetup&&console.timeEnd("gl setup");return!0},render:p,settings:q,valid:function(){return!1!==e},clear:d,flush:b,setXAxis:m,setYAxis:g,data:E,gl:function(){return e},allocateBuffer:function(a){var b=0;q.usePreallocated&&(y(a.series,function(a){a.isSeriesBoosting&&(b+=c(a))}),A.allocate(b))},destroy:function(){b();A.destroy();h.destroy();e&&(H&&e.deleteTexture(H),e.canvas.width=1,e.canvas.height=1)},setOptions:function(a){na(!0,
q,a)}}}function da(a,c){var d=a.chartWidth,f=a.chartHeight,b=a,m=a.seriesGroup||c.group,g=G.implementation.hasFeature("www.http://w3.org/TR/SVG11/feature#Extensibility","1.1"),b=a.isChartSeriesBoosting()?a:c,g=!1;b.renderTarget||(b.canvas=oa,a.renderer.forExport||!g?(b.renderTarget=a.renderer.image("",0,0,d,f).addClass("highcharts-boost-canvas").add(m),b.boostClear=function(){b.renderTarget.attr({href:""})},b.boostCopy=function(){b.boostResizeTarget();b.renderTarget.attr({href:b.canvas.toDataURL("image/png")})}):
(b.renderTargetFo=a.renderer.createElement("foreignObject").add(m),b.renderTarget=G.createElement("canvas"),b.renderTargetCtx=b.renderTarget.getContext("2d"),b.renderTargetFo.element.appendChild(b.renderTarget),b.boostClear=function(){b.renderTarget.width=b.canvas.width;b.renderTarget.height=b.canvas.height},b.boostCopy=function(){b.renderTarget.width=b.canvas.width;b.renderTarget.height=b.canvas.height;b.renderTargetCtx.drawImage(b.canvas,0,0)}),b.boostResizeTarget=function(){d=a.chartWidth;f=a.chartHeight;
(b.renderTargetFo||b.renderTarget).attr({x:0,y:0,width:d,height:f}).css({pointerEvents:"none",mixedBlendMode:"normal",opacity:1});b instanceof l.Chart&&b.markerGroup.translate(c.xAxis.pos,c.yAxis.pos)},b.boostClipRect=a.renderer.clipRect(),(b.renderTargetFo||b.renderTarget).clip(b.boostClipRect),b instanceof l.Chart&&(b.markerGroup=b.renderer.g().add(m),b.markerGroup.translate(c.xAxis.pos,c.yAxis.pos)));b.canvas.width=d;b.canvas.height=f;b.boostClipRect.attr(a.getBoostClipRect(b));b.boostResizeTarget();
b.boostClear();b.ogl||(b.ogl=la(function(){b.ogl.settings.debug.timeBufferCopy&&console.time("buffer copy");b.boostCopy();b.ogl.settings.debug.timeBufferCopy&&console.timeEnd("buffer copy")}),b.ogl.init(b.canvas),b.ogl.setOptions(a.options.boost||{}),b instanceof l.Chart&&b.ogl.allocateBuffer(a));b.ogl.setSize(d,f);return b.ogl}function ea(a,c,d){a&&c.renderTarget&&c.canvas&&!(d||c.chart).isChartSeriesBoosting()&&a.render(d||c.chart)}function fa(a,c){a&&c.renderTarget&&c.canvas&&!c.chart.isChartSeriesBoosting()&&
a.allocateBufferForSingleSeries(c)}function pa(a){var c=!0;this.chart.options&&this.chart.options.boost&&(c="undefined"===typeof this.chart.options.boost.enabled?!0:this.chart.options.boost.enabled);if(!c||!this.isSeriesBoosting)return a.call(this);this.chart.isBoosting=!0;if(a=da(this.chart,this))fa(a,this),a.pushSeries(this);ea(a,this)}var N=l.win,G=N.document,qa=function(){},ga=l.Chart,ca=l.Color,p=l.Series,g=l.seriesTypes,y=l.each,ha=l.extend,ia=l.addEvent,ra=l.fireEvent,sa=l.grep,I=l.isNumber,
na=l.merge,C=l.pick,k=l.wrap,O=l.getOptions().plotOptions,oa=G.createElement("canvas"),U,ja="area arearange column bar line scatter heatmap bubble treemap".split(" "),L={};y(ja,function(a){L[a]=1});ca.prototype.names={aliceblue:"#f0f8ff",antiquewhite:"#faebd7",aqua:"#00ffff",aquamarine:"#7fffd4",azure:"#f0ffff",beige:"#f5f5dc",bisque:"#ffe4c4",black:"#000000",blanchedalmond:"#ffebcd",blue:"#0000ff",blueviolet:"#8a2be2",brown:"#a52a2a",burlywood:"#deb887",cadetblue:"#5f9ea0",chartreuse:"#7fff00",chocolate:"#d2691e",
coral:"#ff7f50",cornflowerblue:"#6495ed",cornsilk:"#fff8dc",crimson:"#dc143c",cyan:"#00ffff",darkblue:"#00008b",darkcyan:"#008b8b",darkgoldenrod:"#b8860b",darkgray:"#a9a9a9",darkgreen:"#006400",darkkhaki:"#bdb76b",darkmagenta:"#8b008b",darkolivegreen:"#556b2f",darkorange:"#ff8c00",darkorchid:"#9932cc",darkred:"#8b0000",darksalmon:"#e9967a",darkseagreen:"#8fbc8f",darkslateblue:"#483d8b",darkslategray:"#2f4f4f",darkturquoise:"#00ced1",darkviolet:"#9400d3",deeppink:"#ff1493",deepskyblue:"#00bfff",dimgray:"#696969",
dodgerblue:"#1e90ff",feldspar:"#d19275",firebrick:"#b22222",floralwhite:"#fffaf0",forestgreen:"#228b22",fuchsia:"#ff00ff",gainsboro:"#dcdcdc",ghostwhite:"#f8f8ff",gold:"#ffd700",goldenrod:"#daa520",gray:"#808080",green:"#008000",greenyellow:"#adff2f",honeydew:"#f0fff0",hotpink:"#ff69b4",indianred:"#cd5c5c",indigo:"#4b0082",ivory:"#fffff0",khaki:"#f0e68c",lavender:"#e6e6fa",lavenderblush:"#fff0f5",lawngreen:"#7cfc00",lemonchiffon:"#fffacd",lightblue:"#add8e6",lightcoral:"#f08080",lightcyan:"#e0ffff",
lightgoldenrodyellow:"#fafad2",lightgrey:"#d3d3d3",lightgreen:"#90ee90",lightpink:"#ffb6c1",lightsalmon:"#ffa07a",lightseagreen:"#20b2aa",lightskyblue:"#87cefa",lightslateblue:"#8470ff",lightslategray:"#778899",lightsteelblue:"#b0c4de",lightyellow:"#ffffe0",lime:"#00ff00",limegreen:"#32cd32",linen:"#faf0e6",magenta:"#ff00ff",maroon:"#800000",mediumaquamarine:"#66cdaa",mediumblue:"#0000cd",mediumorchid:"#ba55d3",mediumpurple:"#9370d8",mediumseagreen:"#3cb371",mediumslateblue:"#7b68ee",mediumspringgreen:"#00fa9a",
mediumturquoise:"#48d1cc",mediumvioletred:"#c71585",midnightblue:"#191970",mintcream:"#f5fffa",mistyrose:"#ffe4e1",moccasin:"#ffe4b5",navajowhite:"#ffdead",navy:"#000080",oldlace:"#fdf5e6",olive:"#808000",olivedrab:"#6b8e23",orange:"#ffa500",orangered:"#ff4500",orchid:"#da70d6",palegoldenrod:"#eee8aa",palegreen:"#98fb98",paleturquoise:"#afeeee",palevioletred:"#d87093",papayawhip:"#ffefd5",peachpuff:"#ffdab9",peru:"#cd853f",pink:"#ffc0cb",plum:"#dda0dd",powderblue:"#b0e0e6",purple:"#800080",red:"#ff0000",
rosybrown:"#bc8f8f",royalblue:"#4169e1",saddlebrown:"#8b4513",salmon:"#fa8072",sandybrown:"#f4a460",seagreen:"#2e8b57",seashell:"#fff5ee",sienna:"#a0522d",silver:"#c0c0c0",skyblue:"#87ceeb",slateblue:"#6a5acd",slategray:"#708090",snow:"#fffafa",springgreen:"#00ff7f",steelblue:"#4682b4",tan:"#d2b48c",teal:"#008080",thistle:"#d8bfd8",tomato:"#ff6347",turquoise:"#40e0d0",violet:"#ee82ee",violetred:"#d02090",wheat:"#f5deb3",white:"#ffffff",whitesmoke:"#f5f5f5",yellow:"#ffff00",yellowgreen:"#9acd32"};
ga.prototype.isChartSeriesBoosting=function(){return C(this.options.boost&&this.options.boost.seriesThreshold,50)<=this.series.length||K(this)};ga.prototype.getBoostClipRect=function(a){var c={x:this.plotLeft,y:this.plotTop,width:this.plotWidth,height:this.plotHeight};a===this&&y(this.yAxis,function(a){c.y=Math.min(a.pos,c.y);c.height=Math.max(a.pos-this.plotTop+a.len,c.height)},this);return c};l.eachAsync=function(a,c,d,f,b,g){b=b||0;f=f||3E4;for(var m=b+f,k=!0;k&&b<m&&b<a.length;)k=c(a[b],b),++b;
k&&(b<a.length?g?l.eachAsync(a,c,d,f,b,g):N.requestAnimationFrame?N.requestAnimationFrame(function(){l.eachAsync(a,c,d,f,b)}):setTimeout(function(){l.eachAsync(a,c,d,f,b)}):d&&d())};p.prototype.getPoint=function(a){var c=a,d=this.xData||this.options.xData||this.processedXData||!1;!a||a instanceof this.pointClass||(c=(new this.pointClass).init(this,this.options.data[a.i],d?d[a.i]:void 0),c.category=c.x,c.dist=a.dist,c.distX=a.distX,c.plotX=a.plotX,c.plotY=a.plotY,c.index=a.i);return c};k(p.prototype,
"searchPoint",function(a){return this.getPoint(a.apply(this,[].slice.call(arguments,1)))});k(p.prototype,"destroy",function(a){var c=this,d=c.chart;d.markerGroup===c.markerGroup&&(c.markerGroup=null);d.hoverPoints&&(d.hoverPoints=sa(d.hoverPoints,function(a){return a.series===c}));d.hoverPoint&&d.hoverPoint.series===c&&(d.hoverPoint=null);a.call(this)});k(p.prototype,"getExtremes",function(a){if(!this.isSeriesBoosting||!this.hasExtremes||!this.hasExtremes())return a.apply(this,Array.prototype.slice.call(arguments,
1))});y(ja,function(a){O[a]&&(O[a].boostThreshold=5E3,O[a].boostData=[],g[a].prototype.fillOpacity=!0)});y(["translate","generatePoints","drawTracker","drawPoints","render"],function(a){function c(c){var d=this.options.stacking&&("translate"===a||"generatePoints"===a),b=!0;this.chart&&this.chart.options&&this.chart.options.boost&&(b="undefined"===typeof this.chart.options.boost.enabled?!0:this.chart.options.boost.enabled);if(!this.isSeriesBoosting||d||!b||"heatmap"===this.type||"treemap"===this.type)c.call(this);
else if(this[a+"Canvas"])this[a+"Canvas"]()}k(p.prototype,a,c);"translate"===a&&(g.column&&k(g.column.prototype,a,c),g.bar&&k(g.bar.prototype,a,c),g.arearange&&k(g.arearange.prototype,a,c),g.treemap&&k(g.treemap.prototype,a,c),g.heatmap&&k(g.heatmap.prototype,a,c))});k(p.prototype,"processData",function(a){function c(a){return d.chart.isChartSeriesBoosting()||(a?a.length:0)>=(d.options.boostThreshold||Number.MAX_VALUE)}var d=this,f=this.options.data;c(f)&&"heatmap"!==this.type&&"treemap"!==this.type&&
!this.options.stacking&&this.hasExtremes&&this.hasExtremes(!0)||(a.apply(this,Array.prototype.slice.call(arguments,1)),f=this.processedXData);(this.isSeriesBoosting=c(f))?this.enterBoost():this.exitBoost&&this.exitBoost()});k(p.prototype,"setVisible",function(a,c,d){a.call(this,c,d);!1===this.visible&&this.canvas&&this.renderTarget&&(this.ogl&&this.ogl.clear(),this.boostClear())});p.prototype.enterBoost=function(){this.alteredByBoost=[];y(["allowDG","directTouch","stickyTracking"],function(a){this.alteredByBoost.push({prop:a,
val:this[a],own:this.hasOwnProperty(a)})},this);this.directTouch=this.allowDG=!1;this.stickyTracking=!0;this.animate=null;this.labelBySeries&&(this.labelBySeries=this.labelBySeries.destroy())};p.prototype.exitBoost=function(){y(this.alteredByBoost||[],function(a){a.own?this[a.prop]=a.val:delete this[a.prop]},this);this.boostClear&&this.boostClear()};p.prototype.hasExtremes=function(a){var c=this.options,d=this.xAxis&&this.xAxis.options,f=this.yAxis&&this.yAxis.options;return c.data.length>(c.boostThreshold||
Number.MAX_VALUE)&&I(f.min)&&I(f.max)&&(!a||I(d.min)&&I(d.max))};p.prototype.destroyGraphics=function(){var a=this,c=this.points,d,f;if(c)for(f=0;f<c.length;f+=1)(d=c[f])&&d.graphic&&(d.graphic=d.graphic.destroy());y(["graph","area","tracker"],function(b){a[b]&&(a[b]=a[b].destroy())})};(function(){var a=0,c,d=["webgl","experimental-webgl","moz-webgl","webkit-3d"],f=!1;if("undefined"!==typeof N.WebGLRenderingContext)for(c=G.createElement("canvas");a<d.length;a++)try{if(f=c.getContext(d[a]),"undefined"!==
typeof f&&null!==f)return!0}catch(b){}return!1})()?(l.extend(p.prototype,{renderCanvas:function(){function a(a,b){var c,d,f,h="undefined"===typeof g.index,l=!0;if(!h&&(O?(c=a[0],d=a[1]):(c=a,d=v[b]),F?(O&&(d=a.slice(1,3)),f=d[0],d=d[1]):D&&(c=a.x,d=a.stackY,f=d-a.y),N||(l=d>=z&&d<=y),null!==d&&c>=A&&c<=e&&l))if(a=Math.ceil(k.toPixels(c,!0)),I){if(void 0===r||a===C){F||(f=d);if(void 0===K||d>L)L=d,K=b;if(void 0===r||f<q)q=f,r=b}a!==C&&(void 0!==r&&(d=n.toPixels(L,!0),w=n.toPixels(q,!0),X(a,d,K),w!==
d&&X(a,w,r)),r=K=void 0,C=a)}else d=Math.ceil(n.toPixels(d,!0)),X(a,d,b);return!h}function c(){ra(d,"renderedCanvas");delete d.buildKDTree;d.buildKDTree();V.debug.timeKDTree&&console.timeEnd("kd tree building")}var d=this,f=d.options||{},b=!1,g=d.chart,k=this.xAxis,n=this.yAxis,p=f.xData||d.processedXData,v=f.yData||d.processedYData,h=f.data,b=k.getExtremes(),A=b.min,e=b.max,b=n.getExtremes(),z=b.min,y=b.max,E={},C,I=!!d.sampling,G,M=!1!==f.enableMouseTracking,w=n.getThreshold(f.threshold),F=d.pointArrayMap&&
"low,high"===d.pointArrayMap.join(","),D=!!f.stacking,H=d.cropStart||0,N=d.requireSorting,O=!p,q,L,r,K,V,W=this.xData||this.options.xData||this.processedXData||!1,X=function(a,b,c){U=a+","+b;M&&!E[U]&&(E[U]=!0,g.inverted&&(a=k.len-a,b=n.len-b),G.push({x:W?W[H+c]:!1,clientX:a,plotX:a,plotY:b,i:H+c}))},b=da(g,d);g.isBoosting=!0;V=b.settings;if(this.visible){if(this.points||this.graph)this.animate=null,this.destroyGraphics();g.isChartSeriesBoosting()?this.markerGroup=g.markerGroup:this.markerGroup=d.plotGroup("markerGroup",
"markers",!0,1,g.seriesGroup);G=this.points=[];d.buildKDTree=qa;b&&(fa(b,this),b.pushSeries(d),ea(b,this,g));g.renderer.forExport||(V.debug.timeKDTree&&console.time("kd tree building"),l.eachAsync(D?d.data:p||h,a,c))}}}),y(["heatmap","treemap"],function(a){g[a]&&k(g[a].prototype,"drawPoints",pa)}),g.bubble&&(delete g.bubble.prototype.buildKDTree,k(g.bubble.prototype,"markerAttribs",function(a){return this.isSeriesBoosting?!1:a.apply(this,[].slice.call(arguments,1))})),g.scatter.prototype.fill=!0,
ha(g.area.prototype,{fill:!0,fillOpacity:!0,sampling:!0}),ha(g.column.prototype,{fill:!0,sampling:!0}),l.Chart.prototype.callbacks.push(function(a){ia(a,"predraw",function(){a.boostForceChartBoost=void 0;a.boostForceChartBoost=K(a);a.isBoosting=!1;!a.isChartSeriesBoosting()&&a.didBoost&&(a.didBoost=!1);a.boostClear&&a.boostClear();a.canvas&&a.ogl&&a.isChartSeriesBoosting()&&(a.didBoost=!0,a.ogl.allocateBuffer(a));a.markerGroup&&a.xAxis&&0<a.xAxis.length&&a.yAxis&&0<a.yAxis.length&&a.markerGroup.translate(a.xAxis[0].pos,
a.yAxis[0].pos)});ia(a,"render",function(){a.ogl&&a.isChartSeriesBoosting()&&a.ogl.render(a)})})):"undefined"!==typeof l.initCanvasBoost?l.initCanvasBoost():l.error(26)})(v)});
