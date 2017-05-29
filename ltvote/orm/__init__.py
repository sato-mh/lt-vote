from contextlib import contextmanager
from sqlalchemy import Column, create_engine, DateTime, Integer, String, text
from sqlalchemy.orm import sessionmaker
from sqlalchemy.ext.declarative import declarative_base
from retry.api import retry_call

Base = declarative_base()


class Vote(Base):
    __tablename__ = 'votes'

    id = Column(Integer, primary_key=True)
    user_id = Column(String(30))
    conference_id = Column(Integer)
    speaker_id = Column(String(30))
    term = Column(Integer)
    token = Column(String(255))

    created_at = Column(DateTime, server_default=text('CURRENT_TIMESTAMP'))
    updated_at = Column(DateTime, server_default=text(
        'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))


class ORM:

    def __init__(self, url, pool_recycle=3600):
        engine = create_engine(url, pool_recycle=pool_recycle)
        self.Session = sessionmaker(bind=engine)

    @contextmanager
    def create_session(self):
        try:
            sess = self.Session()
            retry_call(sess.execute, fargs=['SELECT 1'], tries=6, delay=10)
            yield sess
        finally:
            sess.close()
