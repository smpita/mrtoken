<?php

namespace Hackage\MrToken;

use Webpatser\Uuid\Uuid;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Hackage\MrToken\Interfaces\MrTokenInterface;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MrTokenService
{
    protected $userModel = '';
    protected $keyColumn = '';
    protected $saltColumn = '';
    protected $expiresColumn = '';
    protected $user = null;
    protected $token = null;

    /**
     * Generate a new token for a user.
     * @param  MrTokenInterface $user User model
     * @return mixed string|boolean
     */
    public function generate(MrTokenInterface $user = null)
    {
        $this->_bootstrap($user);
        if(!is_null($this->user))
        {
            $key = $this->user->{$this->keyColumn};
            $expires = $this->_expTimeStamp();
            $salt = Uuid::generate(4)->string;

            $token = $this->_createToken($key, $expires, $salt);

            $this->user->fillable([$this->saltColumn, $this->expiresColumn])->update([
                $this->saltColumn => $salt,
                $this->expiresColumn => $expires,
            ]);
            return $token;
        }
        return false;
    }

    /**
     * Regenerate the last token for the user.
     * @param  MrTokenInterface $user User model
     * @return mixed string|boolean
     */
    public function regenerate(MrTokenInterface $user = null)
    {
        $this->_bootstrap($user);
        if(!is_null($this->user))
        {
            $key = $this->user->{$this->keyColumn};
            $expires = $this->user->{$this->expiresColumn};
            $salt = $this->user->{$this->saltColumn};

            $token = is_null($salt) || is_null($expires)
                ? false
                : $this->_createToken($key, $expires, $salt);
            return $token;
        }
        return false;
    }

    /**
     * Resolve an object from a login token.
     * @param  string $token
     * @return mixed object|boolean
     */
    public function resolve($token = null)
    {
        $this->_bootstrap($token);
        if(!is_null($this->token))
        {
            try {
                $map = Crypt::decrypt($token);
            }
            catch (DecryptException $e) {
                return abort(401,'Invalid token');
            }
            $key = $map['k'];
            $expires = $map['e'];
            $digest = $map['d'];
            $expired = $this->_isExpired($expires);
            if(!$expired)
            {
                try {
                $user = $this->userModel::where($this->keyColumn, $key)->firstOrFail();
                }
                catch (ModelNotFoundException $e) {
                    return abort(401,'Token references invalid user: '.$key);
                }
                $salt = $user->{$this->saltColumn};
                $checksum = $this->_makeChecksum($key, $expires, $salt);
                $authentic = Hash::check($checksum, $digest);
                if($authentic) return $user;
            }
            return abort(401,'Expired token');
        }
        return false;
    }

    protected function _createToken($key, $expires, $salt)
    {
        $checksum = $this->_makeChecksum($key, $expires, $salt);
        $digest = Hash::make($checksum);
        $map = [
            'k' => $key,
            'e' => $expires,
            'd' => $digest,
        ];
        return Crypt::encrypt($map);
    }

    protected function _expTimeStamp()
    {
        return strToTime('+'.config('mrtoken.TOKEN_LIFE_SPAN', 24).' '.config('mrtoken.TIME_UNITS', 'hours'));
    }

    protected function _makeChecksum($key, $expires, $salt)
    {
        return serialize([
            'k' => $key,
            'e' => $expires,
            's' => $salt,
        ]);
    }

    protected function _isExpired($timestamp)
    {
        return time() > $timestamp;
    }

    protected function _bootstrap($data = null)
    {
        if(!is_null($data))
        {
            if($data instanceof MrTokenInterface)
            {
                $this->user = $data;
                $this->keyColumn = $this->user->getMrTokenKeyColumn();
                $this->saltColumn = $this->user->getMrTokenSaltColumn();
                $this->expiresColumn = $this->user->getMrTokenExpiresColumn();
            }
            else
            {
                $this->token = $data;
                $this->userModel = config('mrtoken.USER_MODEL', 'App\User');
                $userModel = new $this->userModel;
                $this->keyColumn = $userModel->getMrTokenKeyColumn();
                $this->saltColumn = $userModel->getMrTokenSaltColumn();
                $this->expiresColumn = $userModel->getMrTokenExpiresColumn();
            }
            return true;
        }
        return false;
    }
}
