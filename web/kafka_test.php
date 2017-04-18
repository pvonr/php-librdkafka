<?php

pcntl_sigprocmask(SIG_BLOCK, array(SIGIO));

$time = microtime(true);

$conf = new RdKafka\Conf();
//$conf->set('debug','all');
$conf->set('internal.termination.signal', SIGIO);
$conf->set("socket.blocking.max.ms", 1);
$conf->set('queue.buffering.max.ms', 1);
$conf->set('socket.nagle.disable', true);


$conf->setDrMsgCb(function ($kafka, $message) {
    if ($message->err) {
    	echo 'message "' . $message->payload .'" failed to be delivered <br/>';
        // message permanently failed to be delivered
    } else {
        echo 'message "' . $message->payload .'" successfully delivered <br/>';
        // message successfully delivered
    }
});

/*
$topicConf = RdKafka\topicConf();
$topicConf->set();
*/

$rk = new RdKafka\Producer($conf);
//$rk->setLogLevel(LOG_DEBUG);


$rk->addBrokers("192.168.0.188:9092");


$topic = $rk->newTopic("topic");


for ($i = 0; $i < 1; $i++) {
    $topic->produce(RD_KAFKA_PARTITION_UA, 0, "Message $i");
    $rk->poll(0);
}

$i = 0;

while ($rk->getOutQLen() > 0) {
    $rk->poll(1);
    $i++;
}

var_dump(microtime(true) - $time);
echo '<br/>';

 
$_st  = microtime(TRUE);
$producer = new \RdKafka\Producer();
$producer->addBrokers('192.168.0.188:9092');
$topic = $producer->newTopic('PHPTest');
$topic->produce(\RD_KAFKA_PARTITION_UA, 0, date(DATE_W3C));
var_dump(microtime(TRUE) - $_st);
