<?php

namespace App\Controller\Traits;

use App\Entity\Subscription;

trait SaveSubscription {
    private function saveSubscription($plan, $user)
    {
        $date = new \DateTime();
        $date->modify('+1 month');
        $subscription = $user->getSubscription();
        if(null === $subscription) {
            $subscription = new Subscription();
        }
        if($subscription->getFreePlanUsed() && $plan == Subscription::getPlanDataNameByIndex(0)) {
            return;
        }
        $subscription->setValidTo($date);
        $subscription->setPlan($plan);
        if($plan == Subscription::getPlanDataNameByIndex(0)) {
            $subscription->setFreePlanUsed(true);
            $subscription->setPaymentStatus('paid');
        } else $subscription->setFreePlanUsed(false);
        $subscription->setPaymentStatus('paid');
        $user->setSubscription($subscription);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
    }
}