<?php

namespace App\Entity\Compose;

use App\Utils\Series;
use App\Utils\Time;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class StatisticsTrait
 * @package App\Entity
 */
trait StatisticsTrait {

    /**
     * @var array
     * @ORM\Column(type="json")
     * @Groups({"stats:read"})
     */
    public $balance = [
        'last_30_days' => [],
        'last_12_months' => [],
        'last_10_years' => [],
    ];

    /**
     * @var array
     * @ORM\Column(type="json")
     * @Groups({"stats:read"})
     */
    public $funding = [
        'last_30_days' => [],
        'last_12_months' => [],
        'last_10_years' => [],
    ];

    /**
     * @var array
     * @ORM\Column(type="json")
     * @Groups({"stats:read"})
     */
    public $profit_and_loss = [
        'last_30_days' => [],
        'last_12_months' => [],
        'last_10_years' => [],
    ];

    /**
     * @var array
     * @ORM\Column(type="json")
     * @Groups({"stats:read"})
     */
    public $summary = [
        'current' => [
            'profit_and_loss' => [
                'day' => [
                    'amount' => 0,
                    'percent' => 0.0
                ],
                'month' => [
                    'amount' => 0,
                    'percent' => 0.0
                ],
                'year' => [
                    'amount' => 0,
                    'percent' => 0.0
                ]
            ]
        ],
        'total' => [
            'profit_and_loss' => 0.0,
            'balance' => 0.0,
            'deposited' => 0.0,
            'withdrawn' => 0.0
        ]
    ];


    public function computeStatistics() {
        $this->profit_and_loss = [
            'last_30_days' => Series::sub($this->balance['last_30_days'], $this->funding['last_30_days']),
            'last_12_months' => Series::sub($this->balance['last_12_months'], $this->funding['last_12_months']),
            'last_10_years' => Series::sub($this->balance['last_10_years'], $this->funding['last_10_years']),
        ];

        $today = Time::getBeginningOfCurrent(Time::PERIOD_DAY)->format('c');
        $yesterday = Time::getBeginningOfPrevious(Time::PERIOD_DAY)->format('c');
        $currentMonth = Time::getBeginningOfCurrent(Time::PERIOD_MONTH)->format('c');
        $lastMonth = Time::getBeginningOfPrevious(Time::PERIOD_MONTH)->format('c');
        $currentYear = Time::getBeginningOfCurrent(Time::PERIOD_YEAR)->format('c');
        $lastYear = Time::getBeginningOfPrevious(Time::PERIOD_YEAR)->format('c');

        $totalBalance = $this->balance['last_30_days'][$today];
        $totalPnL = $this->profit_and_loss['last_30_days'][$today];
        $totalDeposited = $this->funding['last_30_days'][$today];
        $totalWithdrawn = 0;

        $dayPnL = $this->profit_and_loss['last_30_days'][$today] - $this->profit_and_loss['last_30_days'][$yesterday];
        $monthPnL = $this->profit_and_loss['last_12_months'][$currentMonth] - $this->profit_and_loss['last_12_months'][$lastMonth];
        $yearPnL = $this->profit_and_loss['last_10_years'][$currentYear] - $this->profit_and_loss['last_10_years'][$lastYear];
        $dayPercent = $totalDeposited > 0? round($dayPnL / $totalDeposited * 100.0, 2): 0.0;
        $monthPercent = $totalDeposited > 0? round($monthPnL / $totalDeposited * 100.0, 2): 0.0;
        $yearPercent = $totalDeposited > 0? round($yearPnL / $totalDeposited * 100.0, 2): 0.0;

        $this->summary = [
            'current' => [
                'profit_and_loss' => [
                    'day' => [
                        'amount' => $dayPnL,
                        'percent' => $dayPercent
                    ],
                    'month' => [
                        'amount' => $monthPnL,
                        'percent' => $monthPercent
                    ],
                    'year' => [
                        'amount' => $yearPnL,
                        'percent' => $yearPercent
                    ]
                ]
            ],
            'total' => [
                'profit_and_loss' => $totalPnL,
                'balance' => $totalBalance,
                'deposited' => $totalDeposited,
                'withdrawn' => $totalWithdrawn
            ]
        ];
    }

}