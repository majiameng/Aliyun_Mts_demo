<?php
date_default_timezone_set('PRC');
include_once './master/aliyun-php-sdk-core/Config.php';
use Mts\Request\V20140618 as Mts;

//阿里云Mts 提交转码作业
function SubmitJobs($client){
    $pipelineId = '4b036467716c4883825623591e5ea1ee';

    $ossLocation='oss-cn-beijing';//oss-cn-hangzhou、oss-cn-shanghai、oss-us-west-1等;与region对应
    $inputObject='video/59cb4325ca2b4.mp4';//
    $inputBucket='majiameng';

    $outputObject='video/59cb4325ca2b4.mp4';
    $outputBucket='majiameng';
    $transcodeTemplateId='e07b5650ca0caaa8cfc013774b98b054';


    $inputFile = array(
        'Location' => $ossLocation,
        'Bucket' => $inputBucket,
        'Object' => urlencode($inputObject));
    $outputs = array();
    $outputs[] = array(
        'OutputObject'=> urlencode($outputObject),
        'TemplateId' => $transcodeTemplateId,
    );

    $request = new Mts\SubmitJobsRequest();
    $request->setAcceptFormat('JSON');
    $request->setInput(json_encode($inputFile));
    $request->setOutputBucket($outputBucket);
    $request->setOutputLocation($ossLocation);
    $request->setOUtputs(json_encode($outputs));
    $request->setPipelineId($pipelineId);
    $response = $client->getAcsResponse($request);
    return $response;
}


$region = 'cn-beijing';//
$accessKeyId = 'LTAIs69XC********';//
$accessKeySecret = '2MSFmteebW49J8p**********';//

//初始化
$profile = DefaultProfile::getProfile($region, $accessKeyId,$accessKeySecret);
$client = new DefaultAcsClient($profile);

try{
    $response = SubmitJobs($client);
}catch (Exception $exception){
    die($exception->getMessage());
}

print 'job_id is ' + $response->{'JobResultList'}->{'JobResult'}[0]->{'Job'}->{'JobId'};

?>
