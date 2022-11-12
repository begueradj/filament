<?php

namespace Filament\Tables\Testing;

use Closure;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Testing\Assert;
use Livewire\Testing\TestableLivewire;

/**
 * @method HasTable instance()
 *
 * @mixin TestableLivewire
 */
class TestsSummaries
{
    public function assertTableColumnSummarySet(): Closure
    {
        return function (string $columnName, string $summarizerId, $state, bool $isCurrentPaginationPageOnly = false): static {
            /** @phpstan-ignore-next-line */
            $this->assertTableColumnSummarizerExists($columnName, $summarizerId);

            if (is_float($state)) {
                $state = round($state, 5);
            }

            $summarizer = $this->instance()->getTable()->getColumn($columnName)->getSummarizer($summarizerId);

            $query = $isCurrentPaginationPageOnly ?
                $this->instance()->getPageTableSummaryQuery() :
                $this->instance()->getAllTableSummaryQuery();

            $actualState = $summarizer->query($query)->getState();

            if (is_float($actualState)) {
                $actualState = round($actualState, 5);
            }

            $livewireClass = $this->instance()::class;

            Assert::assertSame(
                $state,
                $actualState,
                message: "Failed asserting that summarizer [$summarizerId], for column [{$columnName}], on the [{$livewireClass}] component, is set.",
            );

            return $this;
        };
    }

    public function assertTableColumnSummaryNotSet(): Closure
    {
        return function (string $columnName, string $summarizerId, $state, bool $isCurrentPaginationPageOnly = false): static {
            /** @phpstan-ignore-next-line */
            $this->assertTableColumnSummarizerExists($columnName, $summarizerId);

            if (is_float($state)) {
                $state = round($state, 5);
            }

            $summarizer = $this->instance()->getTable()->getColumn($columnName)->getSummarizer($summarizerId);

            $query = $isCurrentPaginationPageOnly ?
                $this->instance()->getPageTableSummaryQuery() :
                $this->instance()->getAllTableSummaryQuery();

            $actualState = $summarizer->query($query)->getState();

            if (is_float($actualState)) {
                $actualState = round($actualState, 5);
            }

            $livewireClass = $this->instance()::class;

            Assert::assertNotSame(
                $state,
                $actualState,
                message: "Failed asserting that summarizer [$summarizerId], for column [{$columnName}], on the [{$livewireClass}] component, is not set.",
            );

            return $this;
        };
    }

    public function assertTableColumnSummarizerExists(): Closure
    {
        return function (string $columnName, string $summarizerId): static {
            $this->assertTableColumnExists($columnName);

            $column = $this->instance()->getTable()->getColumn($columnName);

            $summarizer = $column->getSummarizer($summarizerId);

            $livewireClass = $this->instance()::class;

            Assert::assertInstanceOf(
                Summarizer::class,
                $summarizer,
                message: "Failed asserting that a table column with name [{$columnName}] has a summarizer with ID [{$summarizerId}] on the [{$livewireClass}] component. Please ensure that the ID is passed to the summarizer with [Summarizer::make('{$summarizerId}')].",
            );

            return $this;
        };
    }
}