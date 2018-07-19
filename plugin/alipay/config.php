<?php
$config = array (
    //应用ID,您的APPID。
    'app_id' => "2018011701928607",

    //商户私钥，您的原始格式RSA私钥
    'merchant_private_key' => "MIIEpQIBAAKCAQEA3CccjfihqJVBLq/sRFNEJXhH7SYkpkL8+YSzrmhRQRSdYHpx
4H+uMTnHoZx6b4vmFja1FDhsfdAWFboVdorTAeRRBVm+TumPtr/B89DA1FnTTX4O
GIkHJBMQ7Ncx6lgnaMtQecojiUgqZPZgX52zhqn5+RTpEI92POesPRLoNu+4Jta+
fuN3QUR0vUHqLbGQecBrCOL5Y6CXkLWgIo4pMAn6vXxo/QSX0MNlHZthIpr8JTh0
+NvuyY2hzp+vNH1bNBWp+2vGE7t4h385uE4q7pwL3rEZAtD7RP8nNNVKa9cHZ7Wd
ArcRRAsSsRQdVI8onwlt6BSt2s4QstV3iK/RRwIDAQABAoIBAQCO8q5t2PVO+6sZ
X42wR8jF87XwXIYLlw+gDPSG2KF5+qK9lIQA26cFu5jObUZqy5IY9mjZulFn2Aef
D/cDw9yDK/cX3E/f+XQhm52abP/oYnL7rFAGymLin6arwodcvOTJ/MpCamhsqXkt
mXS2YTXCyPfRLiyvXhfOMDf8aBx0HjshmlYhUKULjNH/sHWCLeaC81wFoGvrneof
XoHdJ3E8PhRRgFnaMlHfqngHPyqCfT1IXeK3Io9wD/9x704+3rb873LoY73xiKOq
XgHVrVMDHY5CVLyGt7qOctPOrSxYT0BEdaGnqqR/xEyK1GDXMsrzFStckGtgvqbd
8NGSu/uRAoGBAPWA6h18m+JbgrfgRyqmjoutU2bakNVwv4n4KxuJEG1dvKYxKVNx
096ylU6cE+4DPfdhGO+mpVcUMoD1Vmpns21UhhzXUNshKucZqgAWgM2rukgCgLWh
Xtkqi4jdgqNXCNr2kYPH3UEz3vx065Ox1Pt+G0oXCeas6ak8HW/xbqRbAoGBAOWQ
ukDmtc1yFYqwCb6cEEf3gBfGVvGenyHyJmHUbUvCbJwICxDcLNYgZ2KSkijQ/Wpg
dPnXu/MGnCORgAlKLZnq7ZrJOatBdBsRAn/dBxOZoKckFbuQlbQYhKp/BZVA2BWV
VUu4LAZcLyQc5O7fn0vh8XU/y75zAKUikg9wMKqFAoGBAO5luPHZRyaP5mfYCkOI
aXOJZCvEolAhpF29915Nwv3wwHhB1PeK2Uqv7/zd4xyFWW8Xgrd1A6mDm2dO1hON
j9bi9TicfY/MrXSsn0Bmmb0evU2f4Ix/nzMS9Vx6fZlPsvGt7bAiLOBcFnTgtMI/
Narh0m4n4R13W49TKOLDd8VxAoGBAL/EKHJyx0f5lxkleN5rTAZyL+SsYJCyKsiW
mY17gFma7lNhPK0235V3uCVVvxatjiPAs7bJik23JdNohgY8mt9KqnV0xuHSaYT6
rpXVM0YiPVuh/y2R2Bx7pscuGKHXayqMdpYsIUqm0xJduLf6wf0Hn0aMpkxPkShh
OpX+6AhpAoGAH9192FDlm6UNaFf7T4SbyJyoxSZybEG0Qp/s0j+QA1XV/CsGLqfF
J42svsRD8+92cXI1kbCxjx+0j9kMm+sK0eUnE/H/hSr3vBtPMMVpqlWCXLmBlLSp
soq7fg2lEzWGvhWS/9oLBnDN/drL8CN8wm6Qb1jMncfZA76qxlN8Hx8=",

    //异步通知地址
    'notify_url' => "http://工程公网访问地址/alipay.trade.wap.pay-PHP-UTF-8/notify_url.php",

    //同步跳转
    'return_url' => "http://mitsein.com/alipay.trade.wap.pay-PHP-UTF-8/return_url.php",

    //编码格式
    'charset' => "UTF-8",

    //签名方式
    'sign_type'=>"RSA2",

    //支付宝网关
    'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

    //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
    'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAnv/3LYcUlAikKLEwp0/X+pj8e2EhRcN4M6BVIZt14uOYPhsgog5bW07iIBxiaOiI6copHLtN9qbWVYkCCJlSKkEA3Ejf6S+4/6UjFhZHOj5W3WAakGdQsJfkeaDwxUGhyrKVkrc64y78Im3mFv//Nl6ppjYE4CkG667UlGidRxx/jU6nC3k1wayNVDcvtawMN7It0e9+Op3UYbG8S3ce3LTvijCeAAJ3JI9umZQmCRlhgdYpJPfM38YxdFzv5csn4XJbq9fkb/XyDhXmhJKXTqA77346o1oqbxRHOxAlwLR4vUPRj+Ij7TJtB4oEnpILPmpFz1gcFylDvthFngfapwIDAQAB",


);