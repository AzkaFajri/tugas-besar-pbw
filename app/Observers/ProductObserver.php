<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\DetailProduct;
use App\Models\PurchaseOrder;
use App\Models\Invoice;

class ProductObserver
{
    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        // Only sync if unit_price has changed
        if (!$product->isDirty('unit_price')) {
            return;
        }

        $oldPrice = $product->getOriginal('unit_price');
        $newPrice = $product->unit_price;

        // Find all DetailProducts with this product
        $detailProducts = DetailProduct::where('id_product', $product->id_product)->get();

        foreach ($detailProducts as $detail) {
            // Update the amount with new price
            $newAmount = $detail->qty * $newPrice;
            $detail->update(['amount' => $newAmount]);

            // Update the PO totals
            $this->updatePurchaseOrderTotals($detail->id_po);

            // Update related invoices
            $invoices = Invoice::where('id_po', $detail->id_po)->get();
            foreach ($invoices as $invoice) {
                $this->updateInvoiceTotals($invoice->id_po);
            }
        }
    }

    /**
     * Recalculate PO totals from DetailProducts
     */
    private function updatePurchaseOrderTotals($id_po): void
    {
        $subtotal = DetailProduct::where('id_po', $id_po)->sum('amount');
        $ppn = $subtotal * 0.12;
        $grand = $subtotal + $ppn;

        PurchaseOrder::where('id_po', $id_po)->update([
            'subtotal_po' => $subtotal,
            'ppn_po' => $ppn,
            'grand_total_po' => $grand,
        ]);
    }

    /**
     * Recalculate Invoice totals from PO
     */
    private function updateInvoiceTotals($id_po): void
    {
        $po = PurchaseOrder::find($id_po);
        if ($po) {
            Invoice::where('id_po', $id_po)->update([
                'subtotal_invoice' => $po->subtotal_po,
                'ppn_invoice' => $po->ppn_po,
                'grand_total_invoice' => $po->grand_total_po,
            ]);
        }
    }
}
