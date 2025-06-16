<?php

namespace App\Traits;

trait EnumTrait
{

    /**
     * get value by value.
     */
    public static function value( $value ): string
    {
        $value = $value->value ?? $value;

        return !is_null( $value ) && !empty( $value ) ? data_get( self::values(), $value, '' ) : '';
    }

    /**
     * get string from value and array data.
     */
    public static function getFromArray( $data, $value ): string
    {
        $value = $value->value ?? $value;

        return !is_null( $value ) && !empty( $value ) ? data_get( $data, $value, '' ) : '';
    }

    /**
     * get Label from value.
     */
    public static function label( $value ): string
    {
        $value = $value->value ?? $value;

        return !is_null( $value ) && !empty( $value ) ? data_get( self::labels(), $value, '' ) : '';
    }

    /**
     * get all values of cases.
     */
    public static function values(): array
    {
        return collect( self::cases() )->pluck( 'value' )->toArray();
    }

    /**
     * get all names of cases.
     */
    public static function names( int $case = CASE_LOWER ): array
    {
        return array_keys( array_change_key_case( collect( self::cases() )->pluck( 'value', 'name' )->toArray(), $case ) );
    }

    /**
     * get validation rule.
     */
    public static function validationRule( array|string $names = [] ): string
    {
        if ( !blank( $names = is_array( $names ) ? $names : func_get_args() ) )
        {
            $values = collect( self::cases() )->whereIn( 'name', $names )->pluck( 'value' )->toArray();
        }

        return '|in:' . implode( ',', $values ?? self::values() );
    }

    /**
     * get all cases as an array.
     */
    public static function casesToArray( int $case = CASE_LOWER ): array
    {
        return array_change_key_case( collect( self::cases() )->pluck( 'value', 'name' )->toArray(), $case );
    }

    /**
     * get only values of cases by names.
     */
    public static function only( array|string $names = [] ): array
    {
        $names = is_array( $names ) ? $names : func_get_args();

        return collect( self::cases() )->when( count( $names ), function ( $c ) use ( $names ) {
            return $c->whereIn( 'name', $names );
        } )->pluck( 'value' )->toArray();
    }

    /**
     * get except values of cases by names.
     */
    public static function except( array|string $names = [] ): array
    {
        $names = is_array( $names ) ? $names : func_get_args();

        return collect( self::cases() )->whereNotIn( 'name', $names )->pluck( 'value' )->toArray();
    }

    /**
     * Determine if the given name exists in the provided enum cases.
     */
    public static function exists( mixed $name, $caseSensitive = FALSE ): bool
    {
        return (bool) collect( self::cases() )->where( 'name', $caseSensitive ? $name : strtoupper( $name ) )->count();
    }

    /**
     * Determine if the given name exists in the provided only names enum cases.
     */
    public static function existsOnly( mixed $name, array $onlyNames, $caseSensitive = FALSE ): bool
    {
        return (bool) collect( self::cases() )->whereIn( 'name', $onlyNames )->where( 'name', $caseSensitive ? $name : strtoupper( $name ) )->count();
    }

    /**
     * Determine if the given name exists in the provided except names enum cases.
     */
    public static function existsExcept( mixed $name, array $onlyNames, $caseSensitive = FALSE ): bool
    {
        return (bool) collect( self::cases() )->whereNotIn( 'name', $onlyNames )->where( 'name', $caseSensitive ? $name : strtoupper( $name ) )->count();
    }
}
