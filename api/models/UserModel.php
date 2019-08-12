<?php
namespace models;

use libs\Model;
//使用jwt
use Emarref\Jwt\Claim;

class UserModel extends Model{
    protected $table="userinfo";
    protected $key = "";
    protected $expire = -1;

    public function __construct(){
        parent::__construct();
        $this->key = config("userToken.accessKey");
        $this->expire = config("userToken.expire");
    }

    public function getUserInfoByToken($token=''){
        return $this->query("select * from __table__ where usertoken=? limit 1",[$token]);
    }

    public function getUserInfoByName($name=''){
        return $this->query("select * from __table__ where name=? limit 1",[$name]);
    }

    protected function getUserInfoByUid($id = 1) {
        return $this->query("select * from __table__ where id=? limit 1",[$id]);
    }


    //token生成
    public function createToken($user){
        //方法一，随机数生成token
//        $rand=uniqid();
//        $rand=$rand.createRandString($len-strlen($rand));
//        return $rand;

        //方法二，jwt生成token
        $token = new \Emarref\Jwt\Token();//实例化
        $token->addClaim(new Claim\Audience(['audience_1', 'audience_2']));//声明
        $token->addClaim(new Claim\Expiration(new \DateTime($this->expire ." seconds")));//过期时间
        $token->addClaim(new Claim\IssuedAt(new \DateTime('now')));//颁发时间
        $token->addClaim(new Claim\Issuer('lichao'));//颁发人
        $token->addClaim(new Claim\JwtId('1'));//颁发人id
        $token->addClaim(new Claim\NotBefore(new \DateTime('now')));
        $token->addClaim(new Claim\Subject('http://www.mouth6.com'));
//        $token->addClaim(new Claim\PublicClaim('user', $user));
        $token->addClaim(new Claim\PrivateClaim('user', '$user'));

        $jwt = new \Emarref\Jwt\Jwt();
        $algorithm = new \Emarref\Jwt\Algorithm\Hs256($this->key);//使用de算法
        $encryption = \Emarref\Jwt\Encryption\Factory::create($algorithm);
        $serializedToken = $jwt->serialize($token, $encryption);
        return $serializedToken;
    }

    //检测token
    public function verifyToken($token=""){
        $jwt = new \Emarref\Jwt\Jwt();
        $token = $jwt->deserialize($token);
        $algorithm = new \Emarref\Jwt\Algorithm\Hs256($this->key);
        $encryption = \Emarref\Jwt\Encryption\Factory::create($algorithm);

        $context = new \Emarref\Jwt\Verification\Context($encryption);
        $context->setAudience('audience_1');
        $context->setIssuer('lichao');
        $context->setSubject('http://www.mouth6.com');

        try {
            $jwt->verify($token, $context);
        } catch (\Emarref\Jwt\Exception\VerificationException $e) {
            return $e->getMessage();
        }
        // 如果验证通过，返回用户的id
        $user = $token->getPayload()->findClaimByName("user")->getValue();
        //处理user信息
        $user=json_decode($user);
        // 返回用户信息
        return $this->getUserInfoByUid($user['id']) ?? null;
    }
}

?>