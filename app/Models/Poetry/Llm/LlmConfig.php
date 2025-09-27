<?php

namespace App\Models\Poetry\Llm;

class LlmConfig
{
    private const array ALL_PIPE_STRATEGIES = [self::VS_MODE, self::VS_QUALITY, self::VS_RISE_CREATIVITY];

    private const string MODE_STRICT = 'strict';
    private const string MODE_CREATIVELY = 'creatively';
    private const array VS_MODE = [self::MODE_CREATIVELY, self::MODE_STRICT];

    private const string MODE_QUALITY_OK = 'ok';
    private const string MODE_QUALITY_NICE = 'nice';
    private const string MODE_QUALITY_MEGA = 'mega';
    private const array VS_QUALITY = [self::MODE_QUALITY_OK, self::MODE_QUALITY_NICE, self::MODE_QUALITY_MEGA];

    private const string MODE_CREATIVELY_MORE = 'more';
    private const string MODE_CREATIVELY_INSANE = 'insane';
    private const array VS_RISE_CREATIVITY = [self::MODE_CREATIVELY_MORE, self::MODE_CREATIVELY_INSANE];

    private string $llm;
    private array $mode = [self::VS_MODE[0], self::VS_QUALITY[0]];

    public function __construct(string $llm)
    {
        $this->llm = $llm;
    }

    public function llm(): string
    {
        return $this->llm;
    }

    public function pipeMode(): string
    {
        return implode('.', $this->mode);
    }

    public function applyPipeParam(string $param): void
    {
        foreach (self::ALL_PIPE_STRATEGIES as $strategy) {
            foreach ($strategy as $pipeParam) {
                if ($param != $pipeParam) {
                    continue;
                }

                $isParamChanged = false;
                foreach ($this->mode as $ixMode => $mode) {
                    foreach ($strategy as $prevPipeParam) {
                        if ($prevPipeParam == $mode) {
                            $this->mode[$ixMode] = $param;
                            $isParamChanged = true;
                            break 2;
                        }
                    }
                }
                if (!$isParamChanged) {
                    $this->mode[] = $param;
                }

                break 2;
            }
        }
    }

    public static function selectLlmOptions(): array
    {
        return static::simpleSelectOptions(config('basic.llm_models'));
    }

    public static function selectModeOptions(): array
    {
        return static::simpleSelectOptions(self::VS_MODE);
    }

    public static function selectQualityOptions(): array
    {
        return static::simpleSelectOptions(self::VS_QUALITY);
    }

    public static function selectRiseCreativityOptions(): array
    {
        return static::simpleSelectOptions(array_merge(['no'], self::VS_RISE_CREATIVITY));
    }

    private static function simpleSelectOptions(array $options): array
    {
        $return = [];
        foreach ($options as $item) {
            $return[] = [
                'opt' => $item,
                'lbl' => ucfirst($item),
            ];
        }
        return $return;
    }
}
