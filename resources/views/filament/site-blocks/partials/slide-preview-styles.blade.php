{{-- Styles partagés aperçu slide (inline + panneau). --}}
<style>
  .comco-slide-preview__frame {
    position: relative;
    width: 100%;
    background-position: center;
    background-size: cover;
    background-repeat: no-repeat;
    overflow: hidden;
    flex-shrink: 0;
  }
  .comco-slide-preview__frame::before {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(90deg, rgba(15, 23, 42, 0.55), rgba(15, 23, 42, 0.2));
  }
  .comco-slide-preview__inner {
    position: relative;
    z-index: 1;
    display: flex;
    flex-direction: column;
    height: 100%;
    box-sizing: border-box;
    padding: 1.25rem 1.5rem;
  }
  .comco-slide-preview__content {
    display: flex;
    flex-direction: column;
    width: min(100%, 42rem);
    max-width: 100%;
  }
  .comco-slide-preview__title {
    margin: 0;
    line-height: 1.2;
    font-weight: 700;
    font-size: clamp(1.2rem, 2.2vw, 2.1rem);
  }
  .comco-slide-preview__text {
    margin: 0.75rem 0 0;
    line-height: 1.35;
    font-size: clamp(0.9rem, 1.3vw, 1.25rem);
  }
  .comco-slide-preview__actions {
    display: flex;
    flex-wrap: wrap;
    gap: 0.55rem;
    margin-top: 0.9rem;
  }
  .comco-slide-preview__btn {
    display: inline-flex;
    align-items: center;
    padding: 0.45rem 0.85rem;
    font-size: 0.82rem;
    font-weight: 600;
    border: 1px solid transparent;
    white-space: nowrap;
  }

  .comco-slide-phone {
    width: min(100%, 390px);
    height: var(--comco-phone-h, 41.7rem);
    border: 12px solid #0f172a;
    border-radius: 1.6rem;
    overflow: hidden;
    background: #f1f5f9;
    box-shadow: 0 16px 36px rgba(15, 23, 42, 0.22);
    display: flex;
    flex-direction: column;
  }
  .comco-slide-phone__chrome {
    flex-shrink: 0;
    height: 1.6rem;
    background: #0f172a;
    color: #e2e8f0;
    font-size: 0.65rem;
    display: flex;
    align-items: center;
    justify-content: center;
    letter-spacing: 0.04em;
  }
  .comco-slide-phone__rest {
    flex: 1 1 auto;
    min-height: 4rem;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
    padding: 0.85rem 1rem;
    color: #94a3b8;
    font-size: 0.75rem;
  }
  .comco-slide-phone__rest-line {
    height: 0.45rem;
    border-radius: 999px;
    background: #e2e8f0;
    margin-bottom: 0.45rem;
  }
  .comco-slide-phone__rest-line:nth-child(2) { width: 78%; }
  .comco-slide-phone__rest-line:nth-child(3) { width: 62%; }
  .comco-slide-phone .comco-slide-preview__inner {
    padding: 0.85rem;
  }
  .comco-slide-phone .comco-slide-preview__title {
    font-size: 1.05rem;
  }
  .comco-slide-phone .comco-slide-preview__text {
    font-size: 0.82rem;
  }
  .comco-slide-phone .comco-slide-preview__btn {
    font-size: 0.72rem;
    padding: 0.35rem 0.65rem;
  }

  .comco-slide-preview-shell {
    display: flex;
    flex-direction: column;
    gap: 0.85rem;
  }
  .comco-slide-preview-shell__toolbar {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 0.75rem 1rem;
  }
  .comco-slide-preview-shell__toggle {
    display: inline-flex;
    border: 1px solid #d7dde5;
    border-radius: 0.55rem;
    overflow: hidden;
    background: #fff;
  }
  .comco-slide-preview-shell__toggle button {
    appearance: none;
    border: 0;
    background: transparent;
    color: #475569;
    font-size: 0.875rem;
    font-weight: 700;
    padding: 0.45rem 1rem;
    cursor: pointer;
  }
  .comco-slide-preview-shell__toggle button.is-active {
    background: #f59e0b;
    color: #111827;
  }
  .comco-slide-preview-shell__meta {
    font-size: 0.8rem;
    color: #64748b;
  }
  .comco-slide-preview-shell__stage {
    background: #e8edf3;
    border: 1px solid #d7dde5;
    border-radius: 0.75rem;
    padding: 1rem;
  }
  .comco-slide-preview-shell__pc .comco-slide-preview__frame {
    border-radius: 0.5rem;
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.14);
  }
  .comco-slide-preview-shell__pc .comco-slide-preview__inner {
    padding: 1.75rem 2.25rem;
  }
  .comco-slide-preview-shell__mobile {
    display: flex;
    justify-content: center;
  }
  .comco-slide-preview-shell--inline .comco-slide-preview-shell__pc .comco-slide-preview__frame {
    /* Aperçu formulaire : largeur pleine, hauteur réelle du slide */
  }
  .comco-slide-preview-shell--panel {
    min-height: min(72vh, 48rem);
  }
  .comco-slide-preview-shell--panel .comco-slide-preview-shell__stage {
    flex: 1;
    min-height: 26rem;
  }
</style>
