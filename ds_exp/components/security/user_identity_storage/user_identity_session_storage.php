<?php

include_once dirname(__FILE__) . '/../../utils/cookie_manager.php';
include_once dirname(__FILE__) . '/../base_user_auth.php';
include_once dirname(__FILE__) . '/../user_identity.php';
include_once dirname(__FILE__) . '/user_identity_storage.php';
include_once dirname(__FILE__) . '/remember_me_generator.php';

class UserIdentitySessionStorage implements UserIdentityStorage
{
    const KEY_SESSION_IDENTITY = 'current_user';
    const KEY_REMEMBER_ME = 'remember_me';
    const REMEMBER_ME_LIFETIME = 15552000; //3600 * 24 * 180 = 6 months

    /**
     * @var ArrayWrapper
     */
    private $sessionWrapper;

    /**
     * @var CookieManager
     */
    private $cookieManager;

    /**
     * @var IdentityCheckStrategy
     */
    private $identityCheckStrategy;

    /**
     * @var RememberMeGenerator
     */
    private $rememberMeGenerator;

    public function __construct(
        IdentityCheckStrategy $identityCheckStrategy,
        ArrayWrapper $sessionWrapper = null,
        CookieManager $cookieManager = null)
    {
        $this->identityCheckStrategy = $identityCheckStrategy;
        $this->sessionWrapper = is_null($sessionWrapper)
            ? ArrayWrapper::createSessionWrapperForDirectory()
            : $sessionWrapper;
        $this->cookieManager = is_null($cookieManager)
            ? new CookieManager()
            : $cookieManager;
        $this->rememberMeGenerator = new RememberMeGenerator();
    }

    public function SaveUserIdentity(UserIdentity $identity)
    {
        $identity->encryptedPassword = $this->identityCheckStrategy
            ->GetEncryptedPassword($identity->password);
        $this->sessionWrapper->setValue(self::KEY_SESSION_IDENTITY, $identity);

        if ($identity->persistent) {
            $this->cookieManager->setValue(
                self::KEY_REMEMBER_ME,
                $this->rememberMeGenerator->encode($identity),
                self::REMEMBER_ME_LIFETIME
            );
        }
    }

    public function ClearUserIdentity()
    {
        $this->sessionWrapper->unsetValue(self::KEY_SESSION_IDENTITY);
        $this->cookieManager->unsetValue(self::KEY_REMEMBER_ME);
    }

    /**
     * @param string $newPassword
     */
    public function UpdatePassword($newPassword)
    {
        if (!$this->sessionWrapper->isValueSet(self::KEY_SESSION_IDENTITY)) {
            throw new LogicException('cannot update password of the empty user');
        }

        $userIdentity = $this->getUserIdentity();
        $userIdentity->password = $newPassword;
        $this->SaveUserIdentity($userIdentity);
    }

    /**
     * @return UserIdentity|null
     */
    public function getUserIdentity()
    {
        if (!$this->sessionWrapper->isValueSet(self::KEY_SESSION_IDENTITY)) {
            $identity = $this->restoreFromRememberMeCookie();
            if (!$identity instanceof UserIdentity || !$this->identityCheckStrategy->CheckUsernameAndEncryptedPassword($identity->userName, $identity->encryptedPassword)) {
                $this->ClearUserIdentity();

                return null;
            }

            $this->sessionWrapper->setValue(self::KEY_SESSION_IDENTITY, $identity);

            return $identity;
        }

        return $this->sessionWrapper->getValue(self::KEY_SESSION_IDENTITY);
    }

    private function restoreFromRememberMeCookie()
    {
        if (!$this->cookieManager->isValueSet(self::KEY_REMEMBER_ME)) {
            return null;
        }

        return $this->rememberMeGenerator->decode(
            $this->cookieManager->getValue(self::KEY_REMEMBER_ME)
        );
    }
}
