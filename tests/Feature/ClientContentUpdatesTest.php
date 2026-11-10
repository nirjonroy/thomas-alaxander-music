<?php

namespace Tests\Feature;

use Tests\TestCase;

class ClientContentUpdatesTest extends TestCase
{
    /**
     * Update 1: Full Artist EPK (/epk/full-artist)
     */
    public function test_full_artist_epk_renders_signature_interpretations_and_repertoire(): void
    {
        $response = $this->get('/epk/full-artist');

        $response->assertOk();
        $response->assertSee('Stand by Me — Signature Thomas Alexander Interpretation');
        $response->assertSee('Just My Imagination — Signature Thomas Alexander Interpretation');
        $response->assertSee('Signature Interpretations');
        $response->assertSee('Ben E. King');
        $response->assertSee('unmistakably my own');
        $response->assertDontSee('unmistakably your own');

        // Check placement: Signature Interpretations before Performance Formats / Medley
        $content = $response->getContent();
        $posSig = strpos($content, 'Signature Interpretations');
        $posFormat = strpos($content, 'Live Performance Medley') ?: strpos($content, 'Performance Formats');
        $this->assertNotFalse($posSig);
        if ($posFormat !== false) {
            $this->assertLessThan($posFormat, $posSig, 'Signature Interpretations must appear before performance formats/medley.');
        }
    }

    /**
     * Update 2: Crooners EPK (/epk/crooners)
     */
    public function test_crooners_epk_renders_signature_interpretations_and_excludes_repertoire(): void
    {
        $response = $this->get('/epk/crooners');

        $response->assertOk();
        $response->assertSee('Signature Interpretations');
        $response->assertSee('Ben E. King');
        $response->assertSee('unmistakably my own');
        $response->assertDontSee('unmistakably your own');

        // Verify "Stand by Me" is NOT in Crooners Featured Selections
        $content = $response->getContent();
        $posFeatured = strpos($content, 'Featured Selections');
        $posTestimonials = strpos($content, 'Testimonials');
        $posSig = strpos($content, 'Signature Interpretations');

        $this->assertNotFalse($posSig);
        if ($posFeatured !== false) {
            $featuredSection = substr($content, $posFeatured, 1500);
            $this->assertStringNotContainsString('Stand by Me', $featuredSection);
            $this->assertStringNotContainsString('Just My Imagination', $featuredSection);
        }

        // Verify placement: Signature Interpretations before Testimonials
        if ($posTestimonials !== false) {
            $this->assertLessThan($posTestimonials, $posSig, 'Signature Interpretations must appear before Testimonials.');
        }
    }

    /**
     * Update 3: Identity (/identity)
     */
    public function test_identity_page_renders_updated_bio_and_removes_editorial_notes(): void
    {
        $response = $this->get('/identity');

        $response->assertOk();
        $response->assertSee('Thomas Alexander — The Voice', false);
        $response->assertSee('Copper-Skinned BlackIndigenous');
        $response->assertSee('Short Artist Bio');
        $response->assertSee('Full Artist Biography');
        $response->assertSee('Dual-Identity Integration');
        $response->assertSee('Professional Highlights');
        $response->assertSee('Contact & Representation', false);
        $response->assertSee('living-archive#dual-identity');
        $response->assertSee('info@thomasalexanderthevoice.com');
        $response->assertSee('(825) 440-0149');

        // Strip editor notes
        $response->assertDontSee('ABOUT THOMAS — UPDATED FULL PAGE');
        $response->assertDontSee('Executive portrait appears above this section');
        $response->assertDontSee('Note for Nirjon');
        $response->assertDontSee('Note to Nirjon');

        // Verify custom page alias /pages/identity
        $aliasResponse = $this->get('/pages/identity');
        $aliasResponse->assertOk();
        $aliasResponse->assertSee('Copper-Skinned BlackIndigenous');

        // Verify /about-thomas-alexander route synchronization
        $aboutResponse = $this->get('/about-thomas-alexander');
        $aboutResponse->assertOk();
        $aboutResponse->assertSee('Copper-Skinned BlackIndigenous');
        $aboutResponse->assertSee('Short Artist Bio');
        $aboutResponse->assertSee('Full Artist Biography');
    }

    /**
     * Update 4: Five Feathers Lineage Society (/five-feathers-lineage-society)
     */
    public function test_five_feathers_lineage_society_page_renders_complete_society_text(): void
    {
        $response = $this->get('/five-feathers-lineage-society');

        $response->assertOk();
        $response->assertSee('The Five Feathers Lineage Society');
        $response->assertSee('Founding Purpose');
        $response->assertSee('The Living Archive');
        $response->assertSee('The Five Feathers');
        $response->assertSee('Memory, Truth');
        $response->assertSee('Cultural Stewardship');
        $response->assertSee('Companions Along the Journey');
        $response->assertSee('The Breathline');
        $response->assertSee('Our Commitment');

        // Complete approved phrases
        $response->assertSee('One feather remains open for future ancestral confirmation');
        $response->assertSee('We were never erased');
        $response->assertSee('We were replanted');
        $response->assertSee('This is remembrance made living');

        // Verify editorial notes are stripped
        $response->assertDontSee('THE FIVE FEATHERS LINEAGE SOCIETY — UPDATED FULL PAGE');
        $response->assertDontSee('Ceremonial portrait appears above this section');
        $response->assertDontSee('Note for Nirjon');
        $response->assertDontSee('Note to Nirjon');

        // Verify custom page alias /pages/five-feathers-lineage-society
        $aliasResponse = $this->get('/pages/five-feathers-lineage-society');
        $aliasResponse->assertOk();
        $aliasResponse->assertSee('The Five Feathers Lineage Society');

        // Verify 301 redirects
        $this->get('/five-feathers')->assertRedirect('/five-feathers-lineage-society');
        $this->get('/five-feathers-lineage')->assertRedirect('/five-feathers-lineage-society');
        $this->get('/lineage-society')->assertRedirect('/five-feathers-lineage-society');
    }
}
